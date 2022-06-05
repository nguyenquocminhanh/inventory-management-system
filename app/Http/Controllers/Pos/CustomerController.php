<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\PaymentDetail;
use Auth;
use Image;
use Illuminate\Support\Carbon;

use PDF;

class CustomerController extends Controller
{
    public function CustomerAll() {
        $customers = Customer::latest()->get();
        return view('backend.customer.customer_all', compact('customers'));
    }

    public function CustomerAdd() {
        return view('backend.customer.customer_add');
    }

    public function CustomerStore(Request $request) {
        $image = $request->file('image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(200, 200)->save('upload/customer_images/'.$name_gen);
        $save_url = 'upload/customer_images/'.$name_gen;

        Customer::insert([
            'name' => $request->name,
            'image' => $save_url,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'address' => $request->address,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Customer Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('customer.all')->with($notification);
    }

    public function CustomerEdit($id) {
        $customer = Customer::findOrFail($id);
        return view('backend.customer.customer_edit', compact('customer'));
    }

    public function CustomerUpdate(Request $request) {
        $customer_id = $request->id;

        if ($request->file('image')) {
            $image = $request->file('image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(200, 200)->save('upload/customer_images/'.$name_gen);
            $save_url = 'upload/customer_images/'.$name_gen;

            Customer::findOrFail($customer_id)->update([
                'name' => $request->name,
                'image' => $save_url,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'address' => $request->address,
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now(),
            ]);

            $notification = array(
                'message' => 'Customer Updated With Image Successfully',
                'alert-type' => 'success'
            );
    
            return redirect()->route('customer.all')->with($notification);
        } else {
            Customer::findOrFail($customer_id)->update([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'address' => $request->address,
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now(),
            ]);

            $notification = array(
                'message' => 'Customer Updated Without Image Successfully',
                'alert-type' => 'success'
            );
    
            return redirect()->route('customer.all')->with($notification);
        }
    }

    public function CustomerDelete($id) {
        $customer = Customer::findOrFail($id);
        $img = $customer->image;
        unlink($img);

        Customer::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Customer Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('customer.all')->with($notification);
    }



    public function CreditCustomer() {
        // whereIn 2 truong hop xay ra
        // get all payment co paid status la partial_paid hoac full_due
        $allData = Payment::whereIn('paid_status', ['partial_paid', 'full_due'])->get();

        return view('backend.customer.customer_credit', compact('allData'));
    }

    public function CreditCustomerPrintPdf() {
        $allData = Payment::whereIn('paid_status', ['partial_paid', 'full_due'])->get();

        return view('backend.pdf.customer_credit_pdf', compact('allData'));
    }


    public function CustomerEditInvoice($id) {
        $payment = Payment::findOrFail($id);
        return view('backend.customer.customer_invoice_edit', compact('payment'));
    }
    public function CustomerUpdateInvoice(Request $request, $invoice_id) {
        // validate paid amount
        if($request->new_paid_amount < $request->paid_amount) {
            $notification = array(
                'message' => 'Your Paid Greater Than Due Amount',
                'alert-type' => 'error'
            );
    
            return redirect()->back()->with($notification);
        } else {
            $payment = Payment::where('invoice_id', $invoice_id)->first();
            $payment_detail = new PaymentDetail();

            if ($request->paid_status == 'full_paid') {
                $payment->paid_status = $request->paid_status;

                $payment->paid_amount = Payment::where('invoice_id',$invoice_id)->first()['paid_amount'] + $request->new_paid_amount;
                $payment->due_amount = '0';
                $payment_detail->current_paid_amount = $request->new_paid_amount;

            } elseif ($request->paid_status == 'partial_paid') {
                if ($request->paid_amount == Payment::where('invoice_id', $invoice_id)->first()['due_amount']) {
                    $payment->paid_status = 'full_paid';
                } else {
                    $payment->paid_status = $request->paid_status;
                }

               $payment->paid_amount = Payment::where('invoice_id',$invoice_id)->first()['paid_amount'] + $request->paid_amount;
               $payment->due_amount = Payment::where('invoice_id',$invoice_id)->first()['due_amount'] - $request->paid_amount;
               $payment_detail->current_paid_amount = $request->paid_amount;
           }
            $payment->save();
            $payment_detail->invoice_id = $invoice_id;
            $payment_detail->date = date('Y-m-d', strtotime($request->date));
            $payment_detail->updated_by = Auth::user()->id;
            $payment_detail->save();

            $notification = array(
                'message' => 'Invoice Updated Successfully',
                'alert-type' => 'success'
            );
    
            return redirect()->route('credit.customer')->with($notification);
        }
    }


    // detail customer invoice
    public function CustomerInvoiceDetail($id) {
        $payment = Payment::findOrFail($id);

        return view('backend.pdf.invoice_detail_pdf', compact('payment'));
    }



    // paid customer
    public function PaidCustomer() {
        $allData = Payment::where('paid_status', '!=', 'full_due')->get();
        return view('backend.customer.customer_paid', compact('allData'));
    }

    public function PaidCustomerPrintPdf() {
        $allData = Payment::where('paid_status', '!=', 'full_due')->get();

        return view('backend.pdf.customer_paid_pdf', compact('allData'));
    }
    

    // customer wise report
    public function CustomerWiseReport() {
        return view('backend.customer.customer_wise_report');
    }

    public function CustomerWiseCreditPdf(Request $request) {
        // lay payments cua thang khach hang do con no tien
        $allData = Payment::where('customer_id', $request->customer_id)
            ->whereIn('paid_status', ['partial_paid', 'full_due'])->get();

        $customer_id = $request->customer_id;

        return view('backend.pdf.customer_wise_credit_pdf', compact('allData', 'customer_id'));
    }

    public function CustomerWisePaidPdf(Request $request) {
        // lay payments cua thang khach hang do da tra tien
        $allData = Payment::where('customer_id', $request->customer_id)
            ->where('paid_status', '!=', 'full_due')->get();

        $customer_id = $request->customer_id;

        return view('backend.pdf.customer_wise_paid_pdf', compact('allData', 'customer_id'));
    }
}