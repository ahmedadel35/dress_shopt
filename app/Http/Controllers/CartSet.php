<?php

namespace App\Http\Controllers;

use App\Cart;
use App\CartItem;
use App\Http\Resources\CartItemOrderCollection;
use App\Product;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CartSet
{
    const DEFAULT_INSTANCE = null;

    public $init = null;

    public function __construct()
    {
        if (!session()->has('cart')) {
            session()->put('cart', []);
        }
    }

    /**
     * set current instance
     *
     * @param string|null $init
     * @return self
     */
    public function instance(
        ?string $init = self::DEFAULT_INSTANCE
    ): self {
        $this->init = $init;
        return $this;
    }

    /**
     * add new cart to cart list
     *
     * @param integer $productId
     * @param float $price
     * @param integer $qty
     * @param integer $sizeInx
     * @param integer $colorInt
     * @return void
     */
    public function add(
        int $productId,
        float $price,
        int $qty,
        int $sizeInx = 0,
        int $colorInt = 0
    ) {
        $cart = [
            'instance' => $this->init,
        ];

        $item = [
            'product_id' => $productId,
            'qty' => $qty,
            'price' => $price,
            'size' => $sizeInx,
            'color' => $colorInt,
            'instance' => $this->init
        ];

        if (auth()->check()) {
            $cart = Cart::whereUserId(auth()->id())
                ->whereInstance($this->init)
                ->limit(1)
                ->get();

            $item = $cart->first()->items()->create($item);
            return $item;
        }

        $item['id'] = random_int(1, 153648675);
        $item = new CartItem($item);

        session()->push('cart', $item);

        return $item;
    }

    /**
     * get cart item by id
     *
     * @param integer $itemId
     * @return CartItem
     */
    public function get(int $itemId): CartItem
    {
        if (auth()->check()) {
            $item = Cart::whereUserId(auth()->id())
                ->whereInstance($this->init)
                ->limit(1)
                ->with(['items' => function ($q) use ($itemId) {
                    $q->where('id', $itemId);
                }])->get()->first()->items->first();

            if ($item) {
                $item->loadMissing('product');
            }

            return $item;
        }

        $cart = (collect(session('cart'))
            ->whereStrict('id',  $itemId)
            ->whereStrict('instance', $this->init))->first();

        if (is_array($cart)) {
            return new CartItem($cart);
        } elseif ($cart instanceof CartItem) {
            return $cart;
        }

        return new CartItem([]);
    }

    /**
     * get current cart list
     *
     * @return void
     */
    public function content()
    {
        if (auth()->check()) {
            return Cart::whereUserId(auth()->id())
                ->whereInstance($this->init)
                ->limit(1)
                ->with('items')
                ->get()->first()->items;
        }

        $cart = collect(session('cart'))
            ->whereStrict('instance', $this->init);
        return $cart;
    }

    /**
     * get full content
     *
     * @return void
     */
    public function contentResource()
    {
        if (auth()->check()) {
            $cart = Cart::whereUserId(auth()->id())
                ->whereInstance($this->init)
                ->limit(1)
                ->with('items')
                ->get()->first();

            $wish = Cart::whereUserId(auth()->id())
                ->whereInstance('wish')
                ->limit(1)
                ->with('items')
                ->get()->first()->items;

            return (object) ([
                'total' => $cart->total ?? 0,
                'count' => (int) $cart->count ?? 0,
                'items' => $cart->items ?? [],
                'wish' => $wish ?? [],
                'userId' => auth()->id() ?? 0
            ]);
        }

        $cart = collect(session('cart'))
            ->whereStrict('instance', $this->init);

        // dd($cart);
        $total =  $cart->sum(function ($item) {
            if (!($item instanceof CartItem)) {
                $item = new CartItem($item);
            }
            return round($item->qty * $item->price, 2);
        });
        $count = $cart->sum(function ($item) {
            if (!($item instanceof CartItem)) {
                $item = new CartItem($item);
            }
            return $item->qty;
        });

        return (object) [
            'total' => round($total, 2),
            'count' => (int) $count,
            'items' => $cart->values(),
            'wish' => collect(session('cart'))
                ->whereStrict('instance', 'wish')->values(),
            'userId' => auth()->id() ?? 0
        ];
    }

    public function orderContent()
    {
        if (auth()->check()) {
            $cart = Cart::whereUserId(auth()->id())
                ->whereInstance($this->init)
                ->limit(1)
                ->with('items')
                ->get()->first();

            return (object) [
                'total' => (int) (round($cart->total, 2) * 100) ?? 0,
                'items' => (new CartItemOrderCollection($cart->items))->toJson() ?? "[]",
            ];
        }

        $cart = collect(session('cart'))
            ->whereStrict('instance', $this->init);

        // dd($cart);
        $total =  $cart->sum(function ($item) {
            if (!($item instanceof CartItem)) {
                $item = new CartItem($item);
            }
            return round($item->qty * $item->price, 2);
        });

        // dd($cart->values());

        return (object) [
            'total' => (int) (round($total, 2) * 100),
            'items' => (new CartItemOrderCollection($cart->values()))->toJson(),
        ];
    }

    /**
     * check if cart item exists in cart
     *
     * @param integer $itemId
     * @return boolean
     */
    public function has(int $itemId): bool
    {
        if (auth()->check()) {
            return (bool) Cart::whereUserId(auth()->id())
                ->whereInstance($this->init)
                ->limit(1)
                ->with(['items' => function ($q) use ($itemId) {
                    $q->where('id', $itemId);
                }])->get()->first()->items->first();
        }

        return (bool) collect(session('cart'))
            ->whereStrict('instance', $this->init)
            ->whereStrict('id', $itemId)
            ->count();
    }

    /**
     * check if product exists in cart list
     *
     * @param integer $productId
     * @return boolean
     */
    public function exists(int $productId): bool
    {
        if (auth()->check()) {
            $cart = Cart::whereUserId(auth()->id())
                ->whereInstance($this->init)
                ->limit(1)
                ->with(['items' => function ($q) use ($productId) {
                    $q->where('product_id', $productId);
                }])->get()->first();

            return $cart ? (bool) $cart->items->first() : false;
        }

        return (bool) collect(session('cart'))
            ->whereStrict('instance', $this->init)
            ->whereStrict('product_id', $productId)
            ->count();
    }

    /**
     * update cart item in list
     *
     * @param integer $itemId
     * @param object $res
     * @return boolean
     */
    public function update(int $itemId, object $res): bool
    {
        if (auth()->check()) {
            $item = CartItem::find($itemId);
            $item->qty = $res->qty;
            $item->size = $res->size;
            $item->color = $res->color;

            return $item->update();
        }

        $carts = ($this->content())->toArray();
        foreach ($carts as &$item) {
            if (
                (!($item instanceof CartItem)
                    && $item['id'] === $itemId)
                || ($item instanceof CartItem
                    && $item->id === $itemId)
            ) {
                $item = is_array($item) ? new CartItem($item) : $item;
                $item->qty = $res->qty;
                $item->size = $res->size;
                $item->color = $res->color;
            }
        }

        session()->put('cart', $carts);

        return true;
    }

    /**
     * remove an item from cart
     *
     * @param integer $itemId
     * @return void
     */
    public function remove(int $itemId)
    {
        if (auth()->check()) {
            $item = CartItem::find($itemId);

            return $item->delete();
        }

        $carts = $this->content();
        $carts = $carts->filter(function ($item) use ($itemId) {
            if (!($item instanceof CartItem)) {
                $item = new CartItem($item);
            }

            return $item->id !== $itemId && $item->instance === $this->init;
        });

        session()->put('cart', $carts->toArray());
    }

    /**
     * destroy all items for current user
     *
     * @return void
     */
    public function destroy()
    {
        if (auth()->check()) {
            $cart =  Cart::whereUserId(auth()->id())
                ->whereInstance($this->init)
                ->limit(1)
                ->get()->first();
            CartItem::whereCartId($cart->id)->delete();
        }

        $this->init = $this->init === 'wish'
            ? null
            : 'wish';

        $carts = $this->content();
        session()->put('cart', $carts->toArray());
    }

    public function total()
    {
        if (auth()->check()) { }
    }

    public function updateProducts(): self
    {
        if (auth()->check()) {
            return $this;
        }

        $carts = ($this->content())->toArray();

        foreach ($carts as &$item) {
            $item = is_array($item) ? new CartItem($item) : $item;

            $item->product = Product::without(['rates', 'pi'])
                ->whereId($item->product_id)
                ->get()->first();
        }

        session()->put('cart', $carts);
        return $this;
    }

    public function checkProductAmount()
    {
        $carts = ($this->content())->toArray();
        $amountErrors = [];
        $stockOutErrors = [];
        $cartOutput = [];

        foreach ($carts as $item) {
            $item = is_array($item) ? new CartItem($item) : $item;

            if (!auth()->check()) {
                $item->product = Product::without(['rates', 'pi'])
                    ->whereId($item->product_id)
                    ->get()->first();
            }

            $p = $item->product;
            $pqty = is_array($p) ? $p['qty'] : $p->qty;

            if ($item->qty > $pqty) {
                if ($pqty < 1) {
                    // check if product is out of stock
                    $stockOutErrors[] = $item;

                    if (auth()->check()) {
                        DB::table('cart_items')
                            ->delete($item->id);
                    }
                    continue;
                }

                $diff = $item->qty - $pqty;

                // check if diffrence more than 1 else set to 1
                $oldQty = $item->qty;
                $item->qty = $diff <= $pqty
                    ? $diff
                    : 1;

                $amountErrors[] = [
                    'from' => $oldQty,
                    'to' => $item->qty,
                    'item' => $item
                ];
            }

            $cartOutput[] = $item;
        }

        if (sizeof($stockOutErrors)) {
            request()->session()->flash('productOut', $stockOutErrors);
        }

        if (sizeof($amountErrors)) {
            request()->session()->flash('productAmount', $amountErrors);
        }

        session()->put('cart', $cartOutput);
        return $this;
    }

    /**
     * get all cart items from session and 
     * store into the database
     *
     * @return void
     */
    public function afterLoggedIn(User $user)
    {
        $items = collect(session('cart'));

        $items->each(function ($item) use ($user) {
            if (!($item instanceof CartItem)) {
                $item = new CartItem($item);
            }

            // unset($item->instance);
            unset($item->product);
            unset($item->sub_total);
            unset($item->id);

            $cartId = DB::table('carts')
                ->whereUserId($user->id)
                ->whereInstance($item->instance)
                ->get('id');

            $item = CartItem::updateOrInsert([
                'cart_id' => (int) $cartId[0]->id,
                'product_id' => $item->product_id,
                'instance' => $item->instance,
            ],                [
                'qty' => $item->qty,
                'size' => $item->size,
                'color' => $item->color,
                'price' => $item->price
            ]);
        });

        // reset session cart
        session()->put('cart', []);
    }

    /**
     * add empty cart two ids for user
     *
     * @param User $user
     * @return void
     */
    public function afterRegister(User $user): void
    {
        $wish = $user->wishlist;
        if (!$wish) {
            $wish = $user->wishlist()->create([
                'instance' => 'wish'
            ]);
        }
        $cart = $user->cart;
        if (!$cart) {
            $cart = $user->cart()->create([
                'instance' => null
            ]);
        }
    }
}
