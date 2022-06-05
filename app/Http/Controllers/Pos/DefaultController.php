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

class DefaultController extends Controller
{
    public function GetCategory(Request $request) {
        $supplier_id = $request->supplier_id;
        $allCategories = Product::with(['category'])->select('category_id')->
            where('supplier_id', $supplier_id)->groupBy('category_id')->get();
        
        return response()->json($allCategories);
    }

    public function GetProduct(Request $request) {
        $category_id = $request->category_id;
        $allProducts = Product::where('category_id', $category_id)->get();
        
        return response()->json($allProducts);
    }

    public function CheckProductStock(Request $request) {
        $product_id = $request->product_id;

        $stock_qty = Product::where('id', $product_id)->first()->quantity;
        return response()->json($stock_qty);
    }
}
