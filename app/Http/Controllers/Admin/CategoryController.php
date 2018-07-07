<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoryFormReq;
use App\Http\Controllers\Controller;
use App\Models\Album;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::withCount('albums')->withTrashed()->get();
        return view('admin.admincategories')->with('categories', $categories);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryFormReq $request)
    {
        $category = new Category();
        $category->name = $request->input('name');
        $category->save();
        return redirect()->route('categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $category;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryFormReq $request)
    {
        $category = Category::find($request->input('catID'));
        $category->name = $request->input('name');

        $res = $category->save();
        $messaggio = $res ? 'Categoria '.$category->name.' aggiornata' : 'Categoria non aggiornata';
        session()->flash('message', $messaggio);
        return redirect()->route('categories.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $category = Category::withTrashed()->findOrFail($id);
        $res = $request->input('hard') ? $category->forceDelete() : $category->delete();
        return ''.$res;


    }


    public function restore($id){
        $category = Category::withTrashed()->findOrFail($id);
        $category->restore();
        return redirect()->route('categories.index');
    }
}
