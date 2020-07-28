<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GetCategoryList;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use GetCategoryList;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cats = $this->getList();
        $user = auth()->user();

        return view('admin.categories', compact('cats', 'user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $res = (object) $this->validate($request, [
            'ctitle' => 'required|string',
            'img' => 'required|image|mimes:jpg,jpeg,png|max:1024'
        ], [], [
            'ctitle' => __('admin.categories.form.ctitle'),
            'img' => __('admin.categories.form.img')
        ]);

        $category = Category::create([
            'title' => $res->ctitle
        ]);

        $res->img->storeAs(
            'public/collection/',
            $category->id . '.jpg'
        );

        return back()->with('success', __('admin.categories.saved'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $res = (object) $this->validate($request, [
            'ctitle' => 'required|string',
            'img' => 'required|image|mimes:jpg,jpeg,png|max:1024'
        ], [], [
            'ctitle' => __('admin.categories.form.ctitle'),
            'img' => __('admin.categories.form.img')
        ]);

        $category->title = $res->ctitle;
        $category->update();

        $res->img->storeAs(
            'public/collection/',
            $category->id . '.jpg'
        );

        return back()->with('success', __('admin.categories.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->forceDelete();

        return back()->with('success', __('admin.categories.deleted'));
    }
}
