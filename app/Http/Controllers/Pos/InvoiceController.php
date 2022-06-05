<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Unit;
use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use Auth;
use Illuminate\Support\Carbon;

use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Payment;
use App\Models\PaymentDetail;
use App\Models\Customer;

use DB;

class InvoiceController extends Controller
{
    public function InvoiceAll() {
        $invoices = Invoice::orderBy('date', 'DESC')->orderBy('id', 'DESC')->where('status', '1')->get();
        return view('backend.invoice.invoice_all', compact('invoices'));
    }

    public function InvoiceAdd() {
        $categories = Category::all();
        $customers = Customer::all();
        $invoice_data = Invoice::orderBy('id', 'DESC')->first();

        // generate invoice number
        if ($invoice_data == null) {    // there is no invoice in database
            $firstReg = '0';
            $invoice_number = $firstReg + 1;
        } else {
            $invoice_data = Invoice::orderBy('id', 'DESC')->first()->invoice_number;
            $invoice_number = $invoice_data + 1;
        }

        // generate date
        $date = date('Y-m-d');

        return view('backend.invoice.invoice_add', compact('invoice_number', 'date', 'categories', 'customers'));
    }

    public function InvoiceStore(Request $request) {
        if ($request->category_id == null) {
            $notification = array(
                'message' => 'You Did Not Select Any Item',
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification);
        } else {
            if ($request->paid_amount > $request->estimated_amount) {
                $notification = array(
                    'message' => 'Paid amount is greater than the total price',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            } else {
                // add data to Invoice
                $invoice = new Invoice();
                $invoice->invoice_number = $request->invoice_number;
                $invoice->date = date('Y-m-d', strtotime($request->date));
                $invoice->description = $request->description;
                $invoice->status = '0';
                $invoice->created_by = Auth::user()->id;
                $invoice->created_at = Carbon::now();
 
                // use DB when insert multi data to multible tables, if there are any issues -> stop all inserts in any tables
                DB::transaction(function() use($request, $invoice){
                    if($invoice->save()) {
                        $count_category = count($request->category_id);
                        // add data to InvoiceDeTail
                        for($i=0; $i < $count_category; $i++) {
                            $invoice_details = new InvoiceDetail();
                            $invoice_details->date = date('Y-m-d', strtotime($request->date));
                            $invoice_details->invoice_id = $invoice->id;
                            $invoice_details->category_id = $request->category_id[$i];
                            $invoice_details->product_id = $request->product_id[$i];
                            $invoice_details->selling_qty = $request->selling_qty[$i];
                            $invoice_details->unit_price = $request->unit_price[$i];
                            $invoice_details->selling_price = $request->selling_price[$i];
                            $invoice_details->status = '0';

                            $invoice_details->save();
                        }
                        
                        // get customer id
                        if($request->customer_id == '0') { // new customer 
                            $customer = new Customer();
                            $customer->name = $request->name;
                            $customer->phone_number = $request->phone_number;
                            $customer->email = $request->email;
                            $customer->address = $request->address;
                            $customer->created_by = Auth::user()->id;

                            $customer->save();

                            $customer_id = $customer->id;
                        } else {
                            $customer_id = $request->customer_id;
                        }
 
                        // add data to payment
                        $payment = new Payment();
                        $payment_details = new PaymentDetail();

                        $payment->invoice_id = $invoice->id;
                        $payment->customer_id = $customer_id ;
                        $payment->paid_status = $request->paid_status;
                        $payment->discount_amount = $request->discount_amount;
                        $payment->total_amount = $request->estimated_amount;

                        if ($request->paid_status == 'full_paid') {
                            $payment->paid_amount = $request->estimated_amount;
                            $payment->due_amount = '0';
                            $payment_details->current_paid_amount = $request->estimated_amount;
                        } elseif ($request->paid_status == 'full_due') {
                            $payment->paid_amount = '0';
                            $payment->due_amount = $request->estimated_amount;
                            $payment_details->current_paid_amount = '0';
                        } elseif ($request->paid_status == 'partial_paid') {
                            $payment->paid_amount = $request->paid_amount;
                            $payment->due_amount = $request->estimated_amount - $request->paid_amount;
                            $payment_details->current_paid_amount = $request->paid_amount;
                        }

                        $payment->save();

                        $payment_details->invoice_id = $invoice->id;
                        $payment_details->date = date('Y-m-d', strtotime($request->date));
                        $payment_details->save();
                    }
                });
                $notification = array(
                    'message' => 'Invoice Data Inserted Successfully',
                    'alert-type' => 'success'
                );
                return redirect()->route('invoice.pending')->with($notification);
            }
        }
    }

    public function InvoicePending() {
        $invoices = Invoice::orderBy('date', 'DESC')->orderBy('id', 'DESC')
            ->where('status', '0')->get();
        return view('backend.invoice.invoice_pending', compact('invoices'));
    }

    public function InvoiceDelete($id) {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        // delete in other tables
        InvoiceDetail::where('invoice_id', $invoice->id)->delete();
        Payment::where('invoice_id', $invoice->id)->delete();
        PaymentDetail::where('invoice_id', $invoice->id)->delete();

        $notification = array(
            'message' => 'Invoice Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('invoice.pending')->with($notification);
    }

    public function InvoiceApprove($id) {
        // with tu relationship
        // 1 invoice co 1 hoac nhieu invoice_details
        $invoice = Invoice::with('invoice_detail')->findOrFail($id);
        return view('backend.invoice.invoice_approve', compact('invoice'));
    }

    public function InvoiceApproveStore(Request $request, $id) {
        // validate quantity
        foreach($request->selling_qty as $key => $val) {
            // key cua selling_qty se la detail->id 
            $invoice_detail = InvoiceDetail::where('id', $key)->first();
            $product = Product::where('id', $invoice_detail->product_id)->first();

            if ($product->quantity < $val) {
                $notification = array(
                    'message' => 'Current stock of '.$product->name.' is less than selling quantity',
                    'alert-type' => 'error'
                );
                return redirect()->back()->with($notification);
            }
        } 

        // update invoice status
        $invoice = Invoice::findOrFail($id);
        $invoice->updated_by =   Auth::user()->id;
        $invoice->status = '1';

        DB::transaction(function() use($request, $invoice, $id){
            foreach($request->selling_qty as $key => $val) {
                // key cua selling_qty se la detail->id 
                $invoice_detail = InvoiceDetail::where('id', $key)->first();
                $invoice_detail->status = '1';
                $invoice_detail->save();
                $product = Product::where('id', $invoice_detail->product_id)->first();
                
                // minus quantity in Product
                // $val OR $request->selling_qty[$key]
                $product->quantity = ((float)$product->quantity) - ((float)$val);
                $product->save();
            } //end foreach
            $invoice->save();
        });

        $notification = array(
            'message' => 'Invoice Approved Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('invoice.all')->with($notification);
    }



    public function InvoicePrintList() {
        $invoices = Invoice::orderBy('date', 'DESC')->orderBy('id', 'DESC')->where('status', '1')->get();
        return view('backend.invoice.invoice_print_list', compact('invoices'));
    }

    public function PrintInvoice($id) {
        // with tu relationship
        // 1 invoice co 1 hoac nhieu invoice_details
        $invoice = Invoice::with('invoice_detail')->findOrFail($id);
        return view('backend.pdf.invoice_pdf', compact('invoice'));
    }

    

    public function DailyInvoiceReport() {
        return view('backend.invoice.daily_invoice_report');
    }

    public function DailyInvoicePdf(Request $request) {
        // chuyen ve Y-m-d cho giong trong database
        $start_date = date('Y-m-d', strtotime($request->start_date));
        $end_date = date('Y-m-d', strtotime($request->end_date));

        $allData = Invoice::whereBetween('date', [$start_date, $end_date])->where('status', '1')->get();

        return view('backend.pdf.daily_invoice_report_pdf', compact('allData', 'start_date', 'end_date'));
    }
}
