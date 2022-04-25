<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\Rate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Str;

class ProductController extends Controller
{
    use GetCategoryList;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, string $category_slug = '')
    {
        $res = (object) $request->validate([
            'q' => 'sometimes',
            'sort' => 'sometimes|bail|required|string|min:3|max:7',
            'filters' => 'sometimes|bail|required',
            'filters.sizes' => 'sometimes|bail|array',
            'filters.colors' => 'sometimes|bail|array',
            'filters.price.min' => 'sometimes|bail|int|min:1',
            'filters.price.max' => 'sometimes|bail|int',
            'filters.stars' => 'sometimes|bail|int|min:1|max:5'
        ]);

        if ($request->wantsJson()) {
            $products = Product::with('rates');

            if (isset($res->q) && strlen($res->q)) {
                $products->where('title', 'LIKE', "%{$res->q}%")
                    ->orWhere('category_slug', 'LIKE', "%{$res->q}%");
            } else {
                $products->whereCategorySlug($category_slug)->latest();
                $cats = $this->getList();
                if (in_array($category_slug, $cats->pluck('slug')->toArray())) {
                    $cat = $cats->where('slug', $category_slug)
                        ->first();
                    foreach ($cat->sub_cats as $sub) {
                        $products->orWhere('category_slug', $sub->slug);
                    }
                }
            }

            if (isset($res->filters)) {
                $filters = json_decode($res->filters);
                $products = $this->sortBuilder($products, $res->sort ?? '');
                $products = $this->filterProducts($products, $filters);
            } else {
                $products = $this->sortBuilder($products, $res->sort ?? '');
            }

            return response()->json(
                $products->paginate(Product::PER_PAGE)
            );
        }

        return view('products.index', [
            'cats' => $this->getList(),
            'slug' => $category_slug,
            'title' => str_replace('-', ' ', $category_slug)
        ]);
    }

    public function getFeatured()
    {
        $products = Product::inRandomOrder()
            ->where('featured', true)
            ->orderBy('id')
            ->paginate(8);

        return response()->json($products);
    }

    public function getRelated(Product $product)
    {
        $product->makeHidden('rate_avg');
        $tags = $product->tags()->pluck('slug');

        $products = Product::without('rates')
            ->whereHas('tags', function ($q) use ($tags) {
                $q->whereIn('tag_slug', $tags);
            })->where('id', '<>', $product->id)
            ->paginate(8);

        return response()->json($products);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(string $product_slug)
    {
        $product = Product::without('rates')
            ->whereSlug($product_slug)
            ->get()->first();

        abort_unless($product, 404);

        $product->makeHidden('rate_avg');

        if (request()->wantsJson()) {
            if (request()->has('pi')) {
                $product->loadMissing(['tags', 'pi']);
            }

            if (request()->has('avg')) {
                $rate_avg = DB::table('rates')
                    ->selectRaw('AVG(rate) AS rate_avg')
                    ->where('product_id', $product->id)
                    ->get()->first()->rate_avg;

                return response()->json([
                    'prod' => $product->toArray(),
                    'rate_avg' => round($rate_avg, 1)
                ]);
            }

            return response()->json($product->toArray());
        }

        $images = [];
        foreach ($product->images as $img) {
            $images[] = [
                'id' => 'asd' . random_int(5, 6563421),
                'src' => $img
            ];
        }

        return view('products.show', [
            'cats' => $this->getList(),
            'slug' => $product_slug,
            'product' => $product,
            'images' => json_encode($images)
        ]);
    }

    private function sortBuilder(Builder $collection, string $f)
    {
        if ($f === 'highTo') {
            $collection->orderByRaw('(price-(save/100*price)) DESC');
        } elseif ($f === 'lowTo') {
            $collection->orderByRaw('(price-(save/100*price)) ASC');
        } elseif ($f === 'rated') {
            $collection->orderByRaw('(SELECT COUNT(id) FROM rates WHERE rates.product_id = products.id) DESC');
        } else {
            $collection->orderByRaw('(SELECT AVG(rate) FROM rates WHERE rates.product_id = products.id) DESC');
        }

        return $collection;
    }

    private function filterProducts(Builder $collection, object $filters)
    {
        if (sizeof($filters->colors)) {
            $collection = $collection->get();
            $collection = $collection->filter(function (Product $product) use ($filters) {
                return isset(array_intersect(
                    $filters->colors,
                    $product->colors
                )[0]);
            });
        } elseif ($filters->price->max > 0) {
            $collection->whereBetween(
                DB::raw('price-(save/100*price)'),
                [$filters->price->min, $filters->price->max]
            );
        } elseif (sizeof($filters->sizes)) {
            $collection = $collection->get();
            $collection = $collection->filter(function (Product $product) use ($filters) {
                return isset(array_intersect(
                    $filters->sizes,
                    $product->sizes
                )[0]);
            });
        } elseif ($filters->stars > 0) {
            $collection->where(
                DB::raw('(SELECT AVG(rate) AS rate_vgs FROM rates)'),
                '>=',
                $filters->stars
            );
        }

        return $collection;
    }
}
