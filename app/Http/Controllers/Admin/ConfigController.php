<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GetCategoryList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ConfigController extends Controller
{
    use GetCategoryList;

    const SLIDER_UPLOAD_DIR = '/public/site';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders = DB::table('carsoul_images')->get();

        return view('admin.config', [
            'cats' => $this->getList(),
            'user' => auth()->user(),
            'sliders' => $sliders
        ]);
    }

    public function storeImg(Request $request)
    {
        // dd(request()->all());
        $res = (object) $this->validate($request, [
            'title' => 'required|string',
            'url' => 'required|string|url',
            'slider' => 'required|image|mimes:jpg,jpeg,png'
        ]);

        DB::table('carsoul_images')
            ->insert([
                'img' => '/' . Str::replaceFirst(
                    'public',
                    'storage',
                    $res->slider->store(self::SLIDER_UPLOAD_DIR)
                ),
                'title' => $res->title,
                'url' => $res->url
            ]);

        return back()->with('carsuccess', __('admin.config.carscreated'));
    }

    public function destroy(int $id)
    {
        DB::table('carsoul_images')
            ->delete($id);

        return back()->with('carsuccess', __('admin.config.carsdeleted'));
    }
}
