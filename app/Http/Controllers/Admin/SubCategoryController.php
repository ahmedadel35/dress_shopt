<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $res = (object) $this->validate($request, [
            'pcat' => 'required|string|exists:categories,slug',
            'title' => 'required|string'
        ], [], [
            'pcat' => __('admin.categories.form.parent'),
            'title' => __('admin.categories.form.subtitle')
        ]);

        $cat = Category::whereSlug($res->pcat)->limit(1)->first();
        $cat->sub_cats()->create([
            'title' => $res->title
        ]);


        return back()->with('successsub', __('admin.categories.subcatcreated'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $res = (object) $this->validate($request, [
            'pcat' => 'required|string|exists:categories,slug',
            'title' => 'required|string'
        ], [], [
            'pcat' => __('admin.categories.form.parent'),
            'title' => __('admin.categories.form.subtitle')
        ]);

        // $cat = Category::whereSlug($res->pcat)->limit(1)->first();
        $category->title = $res->title;
        $category->update();

        return back()->with('successsub', __('admin.categories.subcatupdated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        // dd($category->toArray());
        $category->forceDelete();

        return back()->with('successsub', __('admin.categories.subcatdeleted'));
    }
}
