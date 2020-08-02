<?php

namespace Tests\Feature;

use App\Address;
use App\Facades\AcceptPay;
use App\Http\Controllers\AcceptOrderController;
use App\Http\Resources\AddressResource;
use CartSet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Response;
use OrderPay;
use Session;
use Tests\TestCase;

class AcceptOrderControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testItCanCreateOrder()
    {
        $userInfo = factory(Address::class)->create();
        [$p, $items] = $this->createCart(null, 4, [23]);

        $cart = CartSet::instance()->orderContent();


        $res = OrderPay::store($userInfo, $cart);

        $this->assertIsObject($res);
        $this->assertIsObject($res->shipping_data);
        $this->assertIsString($res->shipping_data->country);
        $this->assertEquals(23, $res->items[0]->quantity);
    }

    public function testItCanUpdateOrder()
    {
        $cartTotal = 36541;
        $userInfo = factory(Address::class)->create();
        [$p, $items] = $this->createCart(null, 4, [23]);

        $cart = CartSet::instance()->orderContent();

        $res = OrderPay::store($userInfo, $cart);
        $orderId = $res->id;

        $cart->total = $cartTotal;

        $res = OrderPay::update($orderId, $cart);

        $this->assertIsObject($res);
        $this->assertEquals($cartTotal, $res->amount_cents);
    }

    public function testUserCanCreateOrder()
    {
        $this->signIn();
        $this->testItCanCreateOrder();
    }

    public function testUserCanUpdateOrder()
    {
        $this->signIn();
        $this->testItCanUpdateOrder();
    }

    // TODO check how get requests can have body
    // public function testItCanGetOrder()
    // {
    //     $userInfo = (new AddressResource(factory(Address::class)->create()))->toJson();
    //     [$p, $items] = $this->createCart(null, 4, [23]);

    //    $cart = CartSet::instance()->orderContent();

    //    $ress = OrderPay::store($userInfo, $cart);
    //    $orderId = $ress->id;

    //    $res = OrderPay::show($orderId);

    //    $this->assertIsObject($res);
    //    dd($res);
    //    $this->assertEquals(23, $res->items[0]->quantity);
    // }

    public function testItcanDeleteOrder()
    {
        $userInfo = factory(Address::class)->create();
        [$p, $items] = $this->createCart(null, 4, [23]);

        $cart = CartSet::instance()->orderContent();

        $ress = OrderPay::store($userInfo, $cart);
        $orderId = $ress->id;

        $res = OrderPay::destroy($orderId);

        $this->assertEquals(200, $res->getStatusCode());
    }

    public function testUserCanDeleteOrder()
    {
        $this->signIn();
        $this->testItcanDeleteOrder();
    }

    public function testCreatingOrderWillSaveItsIdInDB()
    {
        $this->testItCanCreateOrder();

        $orderRes = (Session::get('orderRes'));

        $this->assertDatabaseHas('order_ids', [
            'id' => $orderRes->id,
            'total' => $orderRes->amount_cents / 100
        ]);
    }

    public function testUserCreateingOrderWillSaveItsIdInDB()
    {
        $this->signIn();
        $this->testCreatingOrderWillSaveItsIdInDB();
    }

    public function testCreatingOrderWillReturnOldIdIfExists()
    {
        $orderRes = AcceptControllerTest::getOrderResponse();
        session()->put('orderRes', $orderRes);

        $userInfo = factory(Address::class)->create();
        [$p, $items] = $this->createCart(null, 4, [23]);

        $cart = CartSet::instance()->orderContent();

        $order = OrderPay::store($userInfo, $cart);

        $this->assertIsObject($order);
        $this->assertEquals(5206230, $order->id);
        $this->assertEquals(975073, $order->amount_cents);
    }
}
