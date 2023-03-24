<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Auth;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function UnitAll()
    {
        $units = Unit::latest()->get();
        return view('admin.backend.unit.unit-all', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function UnitAdd()
    {
        return view('admin.backend.unit.unit-add',);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function UnitStore(Request $request)
    {
        Unit::insert([
            'name' => $request->name,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Unit Insert Successfully', 
            'alert-type' => 'success'
        );

        return redirect()->route('unit.all')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function show(Unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function UnitEdit(Unit $unit, $id)
    {
        $unit = Unit::findOrFail($id);
        return view('admin.backend.unit.unit-edit', compact('unit') );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function UnitUpdate(Request $request, Unit $unit)
    {
        $unit_id = $request->id;
        Unit::findOrfAIL($unit_id)->update([
            'name' => $request->name,
            'updated_by' => Auth::user()->id,
            'updated_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Unit Update Successfully', 
            'alert-type' => 'success'
        );

        return redirect()->route('unit.all')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function UnitDelete(Unit $unit, $id)
    {
        Unit::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Unit Delete Successfully', 
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
