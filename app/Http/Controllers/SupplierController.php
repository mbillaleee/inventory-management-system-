<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Carbon;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function SupplierAll()
    {
        $suppliers = Supplier::latest()->get();
        return view('admin.backend.supplier.supplier-all', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function SupplierAdd()
    {
        return view('admin.backend.supplier.supplier-add',);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function SupplierStore(Request $request)
    {
        Supplier::insert([
            'name' => $request->name,
            'mobile_no' => $request->mobile_no,
            'email' => $request->email,
            'address' => $request->address,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Supplier Insert Successfully', 
            'alert-type' => 'success'
        );

        return redirect()->route('suppliers.all')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function SupplierEdit(Supplier $supplier, $id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('admin.backend.supplier.supplier-edit', compact('supplier') );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function SupplierUpdate(Request $request, Supplier $supplier)
    {
        $supplier_id = $request->id;
        Supplier::findOrfAIL($supplier_id)->update([
            'name' => $request->name,
            'mobile_no' => $request->mobile_no,
            'email' => $request->email,
            'address' => $request->address,
            'update_by' => Auth::user()->id,
            'updated_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Supplier Update Successfully', 
            'alert-type' => 'success'
        );

        return redirect()->route('suppliers.all')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function SupplierDelete(Supplier $supplier, $id)
    {
        Supplier::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Supplier Delete Successfully', 
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
