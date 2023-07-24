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

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function PurchaseAll()
    {
        $allData = Purchase::orderBy('date', 'desc')->orderBy('id', 'desc')->get();
        return view('admin.backend.purchase.purchase-all', compact('allData'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function PurchaseAdd()
    {
        $supplier = Supplier::all();
        $unit = Unit::all();
        $category = Category::all();
        return view('admin.backend.purchase.purchase-add', compact('supplier', 'unit', 'category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function PurchaseStore(Request $request)
    {
        // dd($request->all());
        if($request->category_id == null){
            $notification = array(
                'message' => 'Sorry you do not select any Category', 
                'alert-type' => 'error'
            );
            return redirect()->back()->with($notification);
        }else {

        $count_category = count($request->category_id);
        for ($i=0; $i < $count_category; $i++) { 
            $purchase = new Purchase();
            $purchase->date = date('Y-m-d', strtotime($request->date[$i]));
            $purchase->purchase_no = $request->purchase_no[$i];
            $purchase->supplier_id = $request->supplier_id[$i];
            $purchase->category_id = $request->category_id[$i];

            $purchase->product_id = $request->product_id[$i];
            $purchase->buying_qty = $request->buying_qty[$i];
            $purchase->unit_price = $request->unit_price[$i];
            $purchase->buying_price = $request->buying_price[$i];
            $purchase->description = $request->description[$i];

            $purchase->created_by = Auth::user()->id;
            $purchase->status = '0';
            $purchase->save();
        } // end foreach
    } // end else 
        $notification = array(
            'message' => 'Data save successfully', 
            'alert-type' => 'success'
        );
        return redirect()->route('purchase.all')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function PurchasePending(Purchase $purchase)
    {
        $allPendingData = Purchase::orderBy('date', 'desc')->orderBy('id', 'desc')->where('status', '0')->get();
        return view('admin.backend.purchase.purchase-pending', compact('allPendingData'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function PurchaseDelete(Purchase $purchase, $id)
    {
        Purchase::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Purchase item delete successfully', 
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }
    public function PurchaseApprove(Purchase $purchase, $id)
    {
        $purchase = Purchase::findOrFail($id);
        $product = Product::where('id',$purchase->product_id)->first();
        $purchase_qty = ((float)($purchase->buying_qty))+((float)($product->quantity));
        // dd($purchase_qty);
        $product->quantity = $purchase_qty;
        if($product->save()){
            Purchase::findOrFail($id)->update([
                'status' => '1',
            ]);
            $notification = array(
                'message' => 'Status Approve successfully', 
                'alert-type' => 'success'
            );
            return redirect()->route('purchase.all')->with($notification);
        }  
    }

    public function DailyPurchaseReport(){
        return view('admin.backend.purchase.daily-purchase-report');
    }
    public function DailyPurchasePdf(Request $request){
        $sdate = date('Y-m-d', strtotime($request->start_date));
        $edate = date('Y-m-d', strtotime($request->end_date));
        $allData = Purchase::whereBetween('date', [$sdate, $edate])->where('status', '1')->get();

        $start_date = date('Y-m-d', strtotime($request->start_date));
        $end_date = date('Y-m-d', strtotime($request->end_date));
        return view('admin.backend.pdf.daily-purchase-report-pdf', compact('allData','start_date','end_date'));
    }
}
