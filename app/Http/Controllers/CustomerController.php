<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Payment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Auth;
use Image;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function CustomerAll()
    {
        $customers = Customer::latest()->get();
        return view('admin.backend.customer.customer-all', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function CustomerAdd()
    {
        return view('admin.backend.customer.customer-add',);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function CustomerStore(Request $request)
    {
        $image = $request->file('customer_image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension(); // 343434.png
        Image::make($image)->resize(200,200)->save('upload/customer/'.$name_gen);
        $save_url = 'upload/customer/'.$name_gen;

        Customer::insert([
            'name' => $request->name,
            'mobile_no' => $request->mobile_no,
            'email' => $request->email,
            'address' => $request->address,
            'customer_image' => $save_url,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Customer Insert Successfully', 
            'alert-type' => 'success'
        );

        return redirect()->route('customer.all')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function CustomerEdit(Customer $customer, $id)
    {
        $customer = Customer::findOrFail($id);
        return view('admin.backend.customer.customer-edit', compact('customer') );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function CustomerUpdate(Request $request, Customer $customer)
    {
        $customer_id = $request->id;
        if($request->file('customer_image')){
            $image = $request->file('customer_image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension(); // 343434.png
            Image::make($image)->resize(200,200)->save('upload/customer/'.$name_gen);
            $save_url = 'upload/customer/'.$name_gen;
            Customer::findOrfAIL($customer_id)->update([
                'name' => $request->name,
                'mobile_no' => $request->mobile_no,
                'email' => $request->email,
                'address' => $request->address,
                'customer_image' => $save_url,
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now(),
            ]);

            $notification = array(
                'message' => 'Customer Update Successfully', 
                'alert-type' => 'success'
            );
        }else{
            Customer::findOrfAIL($customer_id)->update([
                'name' => $request->name,
                'mobile_no' => $request->mobile_no,
                'email' => $request->email,
                'address' => $request->address,
                'updated_by' => Auth::user()->id,
                'updated_at' => Carbon::now(),
            ]);

            $notification = array(
                'message' => 'Customer Update Without Image Successfully', 
                'alert-type' => 'success'
            );
        }

        return redirect()->route('customer.all')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function CustomerDelete(Customer $customer, $id)
    {
        $customer = Customer::findOrFail($id); 
        $img = $customer->customer_image;
        unlink($img);
        Customer::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Customer Delete Successfully', 
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function CreditCustomer()
    {
        $allData = Payment::whereIn('paid_status', ['full_due', 'partial_paid'])->get();
        // return $allData;
        return view('admin.backend.customer.customer-credit', compact('allData'));
    }
    public function CreditCustomerPrintPdf()
    {
        $allData = Payment::whereIn('paid_status', ['full_due', 'partial_paid'])->get();
        // return $allData;
        return view('admin.backend.pdf.credit-customer-pdf', compact('allData'));
    }
}
