<?php

namespace Tests\Unit;

use App\Address;
use App\CartItem;
use App\Order;
use App\OrderItem;
use App\Product;
use App\User;
use Hashids;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testItHaveAddress()
    {
        $address = factory(Address::class)->create();
        $order = factory(Order::class)->create([
            'address_id' => $address->id
        ]);

        $this->assertEquals(
            $address->firstName,
            $order->address->firstName
        );
    }

    public function testItHaveItems()
    {
        $order = factory(Order::class)->create();

        $order->items()->saveMany(
            factory(OrderItem::class, 5)->make()
        );

        $this->assertCount(5, $order->items);
    }

    public function testItHaveOwner()
    {
        $user = factory(User::class)->create();
        $order = factory(Order::class)->create([
            'user_id' => $user->id
        ]);

        $this->assertEquals($user->email, $order->owner->email);
    }

    public function testItHaveCreateOrder()
    {
        $order = factory(Order::class)->raw([
            'total' => 555
        ]);

        Order::createNew($order);

        $this->assertDatabaseHas('orders', ['total' => 555]);
    }

    public function testItHaveAddItems()
    {
        $p = factory(Product::class)->create([
            'qty' => 35
        ]);

        [, $items] = $this->createCart($p, 4, [30]);
        $order = factory(Order::class)->create();

        // remove product from items
        $items->each(function (CartItem $item) {
            unset($item->cart_id);
            unset($item->product);
        });

        $order->addItems($items->toArray());

        $this->assertEquals(
            5,
            Product::find($items->first()->product_id)->qty
        );
    }

    public function testItHaveencryptedId()
    {
        $order = factory(Order::class)->create();

        $this->assertEquals(Hashids::encode($order->orderNum), $order->enc_id);
        $this->assertEquals(Hashids::decode($order->enc_id), $order->decId($order->enc_id));
    }
}
