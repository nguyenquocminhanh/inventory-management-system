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

class PurchaseController extends Controller
{
    public function PurchaseAll() {
        $purchases = Purchase::orderBy('date', "DESC")->orderBy('id', 'DESC')->get();
        return view('backend.purchase.purchase_all', compact('purchases'));
    }

    public function PurchaseAdd() {
        $suppliers = Supplier::all();
        $units = Unit::all();
        $categories = Category::all();

        return view('backend.purchase.purchase_add', compact('suppliers', 'units', 'categories'));
    }

    public function PurchaseStore(Request $request) {
        if($request->category_id == null) {
            $notification = array(
                'message' => 'You Did Not Select Any Item',
                'alert-type' => 'error'
            );

            return redirect()->back()->with($notification);
        } else {
            $count_category = count($request->category_id);
            for ($i=0; $i < $count_category; $i++){
                // cach khac insert vao modal
                $purchase = new Purchase();
                $purchase->date = date("Y-m-d", strtotime($request->date[$i]));
                $purchase->purchase_number = $request->purchase_number[$i];
                $purchase->supplier_id = $request->supplier_id[$i];
                $purchase->category_id = $request->category_id[$i];
                $purchase->product_id = $request->product_id[$i];
                $purchase->buying_qty = $request->buying_qty[$i];
                $purchase->unit_price = $request->unit_price[$i];
                $purchase->buying_price = $request->buying_price[$i];
                $purchase->description = $request->description[$i];
                $purchase->created_by = Auth::user()->id;
                $purchase->created_at = Carbon::now();
                $purchase->status = '0';

                $purchase->save();
            } // end for loop

            $notification = array(
                'message' => 'Data Saved Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('purchase.all')->with($notification);
        }
    }

    public function PurchaseDelete($id) {
        Purchase::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Purchase Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('purchase.all')->with($notification);
    }

    public function PurchasePending() {
        $purchases = Purchase::orderBy('date', "DESC")->orderBy('id', 'DESC')
            ->where('status', '0')->get();
        return view('backend.purchase.purchase_pending', compact('purchases'));
    }

    public function PurchaseApprove($id) {
        $purchase = Purchase::findOrFail($id);

        // minh mua san phan -> tang quantity product len 
        $product = Product::where('id', $purchase->product_id)->first();
        $purchase_qty = ((float)($purchase->buying_qty)) + ((float)($product->quantity));
        $product->quantity = $purchase_qty;

        if ($product->save()) {
            Purchase::findOrFail($id)->update([
                'status' => '1'
            ]);

            $notification = array(
                'message' => 'Purchase Approved Successfully',
                'alert-type' => 'success'
            );
    
            return redirect()->route('purchase.all')->with($notification);
        } else {
            $notification = array(
                'message' => 'Something Went Wrong, Try Again!',
                'alert-type' => 'error'
            );
    
            return redirect()->back()->with($notification);
        }
    }



    public function PurchasePrintList() {
        $purchases = Purchase::orderBy('date', 'DESC')->orderBy('id', 'DESC')->where('status', '1')->get();
        return view('backend.purchase.purchase_print_list', compact('purchases'));
    }

    public function PrintPurchase($id) {
        // with tu relationship
        // 1 invoice co 1 hoac nhieu invoice_details
        $purchase = Purchase::findOrFail($id);
        return view('backend.pdf.purchase_pdf', compact('purchase'));
    }



    public function DailyPurchaseReport() {
        return view('backend.purchase.daily_purchase_report');
    }

    public function DailyPurchasePdf(Request $request) {
        // chuyen ve Y-m-d cho giong trong database
        $start_date = date('Y-m-d', strtotime($request->start_date));
        $end_date = date('Y-m-d', strtotime($request->end_date));

        $allData = Purchase::whereBetween('date', [$start_date, $end_date])->where('status', '1')->get();

        return view('backend.pdf.daily_purchase_report_pdf', compact('allData', 'start_date', 'end_date'));
    }
}
