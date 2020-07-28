<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GetCategoryList;
use App\Http\Requests\ProductStoreRequest;
use App\Product;
use App\ProductInfo;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use GetCategoryList;

    const UPLOAD_PATH = '/public/product';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $cats = $this->getList();
        $tags = Tag::all();
        $orderBy = 'created_at DESC';

        if ($request->has('sortBy')) {
            switch ($request->get('sortBy')) {
                case 'highTo':
                    $orderBy .= ', (price-(save/100*price)) DESC';
                    break;
                case 'lowTo':
                    $orderBy .= ', (price-(save/100*price)) ASC';
                    break;
                case 'rated':
                    $orderBy .= ', (SELECT COUNT(id) FROM rates WHERE rates.product_id = products.id) DESC';
                    break;
                default:
                    $orderBy .= ', (SELECT AVG(rate) FROM rates WHERE rates.product_id = products.id) DESC';
                    break;
            }
        }

        $products = Product::without('rates')
            ->orderByRaw($orderBy)
            ->paginate();

        return view('admin.product-list', compact('user', 'cats', 'products', 'tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cats = $this->getList();
        $user = auth()->user();
        $action = '/' . app()->getLocale() . '/root/products';
        $tags = Tag::all();

        return view('admin.product-form', compact(
            'cats',
            'action',
            'user',
            'tags'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStoreRequest $request)
    {
        $res = (object) $request->validated();

        // upload images
        $imgs = [];
        foreach ($res->images as $img) {
            $imgs[] = '/' . Str::replaceFirst(
                'public',
                'storage',
                $img->store(self::UPLOAD_PATH)
            );
        }

        // save product
        $res->images = $imgs;
        $res->user_id = auth()->id();
        $tags = $res->tags;
        $keys = $res->keys;
        $vals = $res->vals;
        unset($res->tags);
        unset($res->keys);
        unset($res->vals);
        $product = Product::create((array) $res);

        // save product tags
        $tags = Tag::whereIn('slug', $tags)->get();
        $product->tags()->sync($tags);

        // save product info
        $data = [];
        for ($i = 0; $i < sizeof($keys); $i++) {
            $val = $vals[$i];
            if ($val === 'true' || $val === 'false') {
                $val = (bool) $val;
            }
            $data[$keys[$i]] = $val;
        }
        $product->pi()->create([
            'more' => $data
        ]);

        return response()->json($product->toArray());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductStoreRequest $request, Product $product)
    {
        $res = (object) $request->validated();

        // upload images
        $imgs = [];
        if (isset($res->images)) {
            foreach ($res->images as $img) {
                $imgs[] = '/' . Str::replaceFirst(
                    'public',
                    'storage',
                    $img->store(self::UPLOAD_PATH)
                );
            }
            $res->images = $imgs;
        }

        // save product
        $res->user_id = auth()->id();
        $tags = $res->tags;
        $keys = $res->keys;
        $vals = $res->vals;
        unset($res->tags);
        unset($res->keys);
        unset($res->vals);
        $product->update((array) $res);

        // save product tags
        $tags = Tag::whereIn('slug', $tags)->get();
        $product->tags()->sync($tags);

        // save product info
        $data = [];
        for ($i = 0; $i < sizeof($keys); $i++) {
            $val = $vals[$i];
            if ($val === 'true') {
                $val = (bool) $val;
            } elseif ($val === 'false') {
                $val = false;
            }
            $data[$keys[$i]] = $val;
        }
        $product->pi->update([
            'more' => $data
        ]);

        return response()->json($product->toArray());
    }

    public function setFeatured(Request $request, Product $product)
    {
        $res = (object) $request->validate([
            'feat' => 'sometimes'
        ]);

        $product->featured = $request->get('feat', false);
        $updated = $product->update();

        return response()->json(compact('updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->noContent();
    }
}
