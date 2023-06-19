<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function StockReport(){
        $allData = Product::orderBy('supplier_id', 'asc')->orderBy('category_id', 'asc')->get();
        return view('admin.backend.stock.stock-report', compact('allData'));
    } //End method
    
    public function StockReportPdf(){
        $allData = Product::orderBy('supplier_id', 'asc')->orderBy('category_id', 'asc')->get();
        return view('admin.backend.pdf.stock-report-pdf', compact('allData'));
    } //End method
    public function StockSupplierWise(){
        $suppliers = Supplier::all();
        $category = Category::all();
        return view('admin.backend.stock.supplier-product-wise-report', compact('suppliers', 'category'));
    } //End method

    public function SupplierWisePdf(Request $request){  //supplier wise request id match product table supplier id
        $allData = Product::orderBy('supplier_id', 'asc')->orderBy('category_id', 'asc')->where('supplier_id', $request->supplier_id)->get();
        return view('admin.backend.pdf.supplier-wise-report-pdf', compact('allData'));
    } //End method

    public function ProductWisePdf(Request $request){  //supplier wise request id match product table supplier id
        $product = Product::where('category_id', $request->category_id)->where('id', $request->product_id)->first();

        return view('admin.backend.pdf.product-wise-report-pdf', compact('product'));
    } //End method
}