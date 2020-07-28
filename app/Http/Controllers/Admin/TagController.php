<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GetCategoryList;
use App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
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
        $tags = Tag::withCount('products')->get();

        return view('admin.tags', compact('cats', 'user', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $res = $this->validate($request, [
            'title' => 'required|string'
        ], [], [
            'title' => __('admin.tags.form.title')
        ]);

        Tag::create($res);

        return back()->with('success', __('admin.tags.form.created'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        $res = $this->validate($request, [
            'title' => 'required|string'
        ], [], [
            'title' => __('admin.tags.form.title')
        ]);

        $tag->title = $res['title'];
        $tag->update();

        return back()->with('success', __('admin.tags.form.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        $tag->forceDelete();

        return back()->with('success', __('admin.tags.form.deleted'));
    }
}
