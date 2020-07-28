<?php

namespace App\Http\Controllers;

use App\CartItem;
use App\Product;
use CartSet;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use GetCategoryList;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(
            CartSet::instance()->contentResource()
        );
    }

    public function checkAmount()
    {
        // CartSet::instance()->content()->each(function ($item) {
        //     $item = is_array($item) ? new CartItem($item) : $item;
        //     $item->qty = (int) $item->qty * 550;
        //     if (random_int(0, 1)) {
        //         $p = Product::find($item->product_id);
        //         $p->qty = 0;
        //         $p->update();
        //         $item->product = $p;
        //     }
        // });
        $cart = CartSet::instance()->checkProductAmount();

        return view('cart.checkout', [
            'cats' => $this->getList(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {
        [
            'qty' => $qty,
            'size' => $size,
            'color' => $color
        ] = $request->validate($this->getValidationArray($product));

        abort_unless($product->qty > 1, 404);

        // check if product exists in cart
        if (CartSet::instance(
            $request->has('wish') ? 'wish' : null
        )->exists($product->id)) {
            return response()->json(['exists' => true]);
        }

        $item = $product->addToCart(
            $qty,
            $size,
            $color,
            $request->has('wish')
        );
        $item->product = $product->makeHidden('rate_avg');

        return response()->json(compact('item'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return view('cart.view', [
            'cats' => $this->getList()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function update(
        Request $request,
        Product $product,
        int $itemId
    ) {
        // check if cart empty
        if (!CartSet::instance()->content()->count()) {
            return response()->json(['empty'], 200);
        }

        // check if item exitst in cart
        if (!CartSet::instance()->has($itemId)) {
            return response()->json(['not_found'], 404);
        }

        $res =  (object) $request->validate(
            $this->getValidationArray($product)
        );

        return response()->json([
            'updated' => CartSet::instance()->update($itemId, $res)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cart  $cart
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $itemId)
    {
        $wish = request()->has('wish') ? 'wish' : null;

        // check if cart empty
        if (!CartSet::instance($wish)->content()->count()) {
            return response()->json(['empty'], 200);
        }

        // check if item exitst in cart
        if (!CartSet::instance($wish)->has($itemId)) {
            return response()->json(['not_found'], 404);
        }

        CartSet::instance($wish)->remove($itemId);

        return response()->json([], 204);
    }

    /**
     * destroy all cart items
     *
     * @return void
     */
    public function destroyAll()
    {
        // check if cart empty
        if (!CartSet::content()->count()) {
            return response()->json(['empty'], 200);
        }

        CartSet::destroy();

        return response()->json([], 204);
    }

    /**
     * destroy user wishlist
     *
     * @return void
     */
    public function destroyAllWish()
    {
        // check if cart empty
        if (!CartSet::instance('wish')->content()->count()) {
            return response()->json(['empty'], 200);
        }

        CartSet::instance('wish')->destroy();

        return response()->json([], 204);
    }

    private function getValidationArray(Product $product): array
    {
        return [
            'qty' => 'required|int|min:1|max:' . $product->qty,
            'size' => 'required|int|min:0|max:' . sizeof(
                $product->sizes
            ),
            'color' => 'required|int|min:0|max:' . sizeof(
                $product->colors
            )
        ];
    }
}
