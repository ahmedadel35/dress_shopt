<?php

namespace Tests\Feature;

use App\Address;
use App\CartItem;
use App\Order;
use App\OrderItem;
use CartSet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testOrdersInfoPageWillNotOpenIfNotItemsInCart()
    {
        $this->get('/en/order/user-info')
            ->assertStatus(404);
    }

    public function testGestCannotOrderWithInvalidData()
    {
        $this->post('/order')
            ->assertStatus(302)
            ->assertSessionHasErrors(['total', 'qty']);
    }

    public function testGuestCanAddOrder()
    {
        $this->withoutExceptionHandling();
        [, $items] = $this->createCart(null, 5);

        $cart = CartSet::instance()->contentResource(false);

        $order = factory(Order::class)->raw([
            'paymentMethod' => 'onDeliver'
        ]);

        $order['items'] = $cart->items->toArray();
        $this->assertCount(5, CartSet::instance()->content());

        $orderTotal = is_int($cart->total) ? $cart->total + .0 : $cart->total;

        $this->postJson('/order', $order)
            ->assertOk()
            ->assertSessionDoesntHaveErrors()
            ->assertJsonPath('user_id', auth()->id())
            ->assertJsonPath('qty', $cart->count)
            ->assertJsonPath('total', $orderTotal);

        $this->assertDatabaseHas('orders', [
            'id' => 1,
            'total' => $cart->total,
        ]);

        $item = $items->first();
        $this->assertDatabaseHas('order_items', [
            'qty' => $item->qty,
            'price' => $item->price,
            'size' => $item->size,
            'sub_total' => round($item->qty * $item->price, 2),
        ]);
        $this->assertCount(0, CartSet::instance()->content());
        $this->assertDatabaseMissing('cart_items', ['id' => 1]);
    }

    public function testUserCanAddOrder()
    {
        $user = $this->signIn();

        $this->testGuestCanAddOrder();

        $this->assertDatabaseHas('orders', [
            'id' => 1,
            'user_id' => $user->id
        ]);
    }

    // public function testGuestCannotUpdateOrderwithInvalidData()
    // {
    //     $order = factory(Order::class)->create();

    //     $this->patchJson('/order/' . $order->orderNum)
    //         ->assertStatus(422);
    // }

    // public function testGuestCanUpdateOrder()
    // {
    //     $order = factory(Order::class)->create();

    //     $this->patchJson('/order/' . $order->orderNum, [
    //         'paymentMethod' => 'paypal'
    //     ])->assertOk()
    //         ->assertJson(['updated' => true]);

    //     $this->assertDatabaseHas('orders', [
    //         'id' => $order->id,
    //         'paymentMethod' => 'paypal'
    //     ]);
    // }

    // public function testUserCanUpdateOrderPayment()
    // {
    //     $this->signIn();
    //     $this->testGuestCanUpdateOrder();
    // }

    public function testGuestCanCheckForOrderValidateionWithoutSaving()
    {
        [, $items] = $this->createCart(null, 5);

        $items = CartSet::instance()->content();

        $items->each(function ($i) {
            unset($i->cart_id);
            unset($i->product);
        });

        $order = factory(Order::class)->raw();

        $order['items'] = $items->toArray();
        $this->assertCount(5, CartSet::instance()->content());

        $res = $this->postJson('/order', $order + ['check' => true])
            ->assertOk()
            ->assertSessionDoesntHaveErrors()
            ->assertJsonPath('total', $order['total']);

        // dd($res->dump()->json());

        $this->assertDatabaseMissing('orders', [
            'id' => 1,
            'total' => $order['total']
        ]);
        $this->assertCount(5, CartSet::instance()->content());
    }

    public function testUserCanCheckForOrder()
    {
        $this->signIn();
        $this->testGuestCanCheckForOrderValidateionWithoutSaving();
    }

    public function testGuestCanNotSaveOrderWithAcceptPayment()
    {
        // $this->withoutExceptionHandling();
        [, $items] = $this->createCart(null, 5);

        $items = CartSet::instance()->content();

        $items->each(function ($i) {
            unset($i->cart_id);
            unset($i->product);
        });

        $order = factory(Order::class)->raw([
            'paymentMethod' => 'accept'
        ]);

        $order['items'] = $items->toArray();
        $this->assertCount(5, CartSet::instance()->content());

        $orderTotal = is_int($order['total']) ? $order['total'] + .0 : $order['total'];

        $this->postJson('/order', $order)
            ->assertForbidden();
    }

    public function testUserCanNotAddAcceptPaymentOrder()
    {
        $this->signIn();
        $this->testGuestCanNotSaveOrderWithAcceptPayment();
    }

    public function testGuestCanGetAcceptPaymentUri()
    {
        $this->withoutExceptionHandling();
        [, $items] = $this->createCart(null, 6);
        $address = factory(Address::class)->raw();
        $order = factory(Order::class)->raw([
            'paymentMethod' => 'accept'
        ]);

        $order['items'] = $items->toArray();
        $this->assertCount(6, CartSet::instance()->content());

        $this->postJson('/order/payment', $order + ['address' => $address])
            ->assertOk()
            ->assertSessionHas('orderRes');
    }

    public function testUserCanGetPaymentUri()
    {
        $this->signIn();
        $this->testGuestCanGetAcceptPaymentUri();
    }
}
