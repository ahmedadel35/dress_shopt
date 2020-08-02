<?php

namespace Tests\Unit;

use App\Cart;
use App\CartItem;
use App\Http\Resources\CartItemOrderCollection;
use App\Http\Resources\CartItemOrderResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartItemTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testItHaveCasts()
    {
        $item = factory(CartItem::class)->create();

        $this->assertIsInt($item->qty);
        $this->assertIsFloat($item->price);
        $this->assertIsInt($item->size);
        $this->assertIsInt($item->color);
    }

    public function testItHasSubTotal()
    {
        $item = factory(CartItem::class)->create([
            'price' => 5,
            'qty' => 2
        ]);

        $this->assertEquals(10, $item->sub_total);
    }

    // public function testItHasParentCartWithTotal()
    // {
    //     $c = factory(Cart::class)->create();
    //     $item = factory(CartItem::class)->create([
    //         'cart_id' => $c->id
    //     ]);

    //     $this->assertEquals($c->id, $item->cart->id);
    // }

    public function testItHaveResource()
    {
        $cartItem = factory(CartItem::class)->create([
            'qty' => 5
        ]);
        $res = (new CartItemOrderResource($cartItem))->toJson();
        $obj = json_decode($res);
        $this->assertJson($res);
        $this->assertEquals(5, $obj->quantity);
        $this->assertNotNull($obj->name);
    }

    public function testItHaveCollectionResource()
    {
        $cartItems = factory(CartItem::class, 2)->create();

        $res = (new CartItemOrderCollection($cartItems))->toJson();

        $arr = json_decode($res);
        $this->assertJson($res);
        $this->assertIsArray($arr);
    }
}
