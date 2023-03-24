<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function CategoryAll()
    {
        $categories = Category::latest()->get();
        return view('admin.backend.category.category-all', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function CategoryAdd()
    {
        return view('admin.backend.category.category-add',);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function CategoryStore(Request $request)
    {
        Category::insert([
            'name' => $request->name,
            'created_by' => Auth::user()->id,
            'created_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Category Insert Successfully', 
            'alert-type' => 'success'
        );

        return redirect()->route('category.all')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function CategoryEdit(Category $category, $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.backend.category.category-edit', compact('category') );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function CategoryUpdate(Request $request, Category $category)
    {
        $category_id = $request->id;
        Category::findOrfAIL($category_id)->update([
            'name' => $request->name,
            'updated_by' => Auth::user()->id,
            'updated_at' => Carbon::now(),
        ]);

        $notification = array(
            'message' => 'Category Update Successfully', 
            'alert-type' => 'success'
        );

        return redirect()->route('category.all')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function CategoryDelete(Category $category, $id)
    {
        Category::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Category Delete Successfully', 
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }
}
