<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class DefaultController extends Controller
{
    public function GetCategory(Request $request){
        $supplier_id = $request->supplier_id;
        // dd($supplier_id);
        $allCategory = Product::with(['category'])->select('category_id')->where('supplier_id', $supplier_id)->groupBy('category_id')->get();
        // dd($allCategory);
        return response()->json($allCategory);
    }
    public function GetProduct(Request $request){
        $category_id = $request->category_id;
        // dd($category_id);
        $allProduct = Product::where('category_id', $category_id)->get();
        // dd($allCategory);
        return response()->json($allProduct);
    }
    public function GetProductStock(Request $request){
        $product_id = $request->product_id;
        // dd($product_id);
        $stockProduct = Product::where('id', $product_id)->first()->quantity;
        // dd($stockProduct);
        return response()->json($stockProduct);
    }
}
