<?php

namespace Tests\Unit;

use App\Address;
use App\Cart;
use App\CartItem;
use App\Order;
use App\Product;
use App\Rate;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Vinkla\Hashids\Facades\Hashids;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testUserCanBeAdmin()
    {
        $u = factory(User::class)->create(['role' => User::AdminRole]);
        $this->assertTrue($u->isAdmin());
        $u = factory(User::class)->create();
        $this->assertFalse($u->isAdmin());
    }

    public function testUserCanBeSuper()
    {
        $u = factory(User::class)->create(['role' => User::SuperRole]);
        $this->assertTrue($u->isSuper());
        $u = factory(User::class)->create();
        $this->assertFalse($u->isSuper());
    }

    public function testUserCanBeDelivery()
    {
        $u = factory(User::class)->create(['role' => User::DeliveryRole]);
        $this->assertTrue($u->isDelivery());
        $u = factory(User::class)->create();
        $this->assertFalse($u->isDelivery());
    }

    public function testUserHaveImage()
    {
        $u = factory(User::class)->create(['img' => '1.jpg']);

        $this->assertEquals(
            url('storage/user_profile/1.jpg'),
            $u->image
        );
    }

    public function testUserHasProducts()
    {
        $user = factory(User::class)->create();

        $this->assertIsIterable($user->products);

        $user->products()->saveMany(
            factory(Product::class, 7)->make()
        );

        $user->load('products');

        $this->assertCount(7, $user->products);
    }

    public function testUserHaveRate()
    {
        $user = factory(User::class)->create();
        $user->rates()->saveMany(
            factory(Rate::class, 4)->make()
        );

        $this->assertCount(4, $user->rates);
    }

    public function testUserHaveCart()
    {
        $user = factory(User::class)->create();

        $user->cart()->save(
            factory(Cart::class)->make()
        );

        // $this->assertCount(1, $user->cart);
        $this->assertNull($user->cart->instance);
    }

    public function testUserHaveWishlist()
    {
        $user = factory(User::class)->create();

        $user->cart()->save(
            factory(Cart::class)->make()
        );

        $user->wishlist()->save(
            factory(Cart::class)->make([
                'instance' => 'wish'
            ])
        );

        $this->assertEquals('wish', $user->wishlist->instance);

        $user->cart->items()->saveMany(
            factory(CartItem::class, 10)->make()
        );

        $user->wishlist->items()->saveMany(
            factory(CartItem::class, 5)->make()
        );

        $this->assertCount(5, $user->wishlist->items);
        $this->assertCount(10, $user->cart->items);
    }

    public function testUserHaveAddresses()
    {
        $user = factory(User::class)->create();

        $address = $user->addresses()->saveMany(
            factory(Address::class, 3)->make()
        );

        $this->assertCount(3, $user->addresses);
    }

    public function testUserHaveOrders()
    {
        $user = factory(User::class)->create();

        $orders = $user->orders()->saveMany(
            factory(Order::class, 5)->make([
                'user_id' => $user->id
            ])
        );

        $this->assertCount(5, $user->orders);
    }

    public function testUserHaveEncryptedId()
    {
        $user = factory(User::class)->create();

        $this->assertEquals(Hashids::encode($user->id), $user->enc_id);
        $this->assertEquals(Hashids::decode($user->enc_id), $user->decId($user->enc_id));
    }
}
