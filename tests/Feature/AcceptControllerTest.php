<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use AcceptPay;
use App\Address;
use App\Facades\OrderPay;
use App\Http\Controllers\AcceptController;
use App\Http\Resources\AddressResource;
use CartSet;
use Illuminate\Support\Facades\Session;

class AcceptControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testItHaveApiKey()
    {
        $this->assertIsString(AcceptPay::getApiKey());
    }

    public function testItCanGetAuthToken()
    {
        $this->assertIsString(AcceptPay::getAuthToken()->getToken());
    }

    public function testItCanGetPaymentKey()
    {
        $userInfo = factory(Address::class)->create();
        [$p, $items] = $this->createCart(null, 4, [23]);

        $cart = CartSet::instance()->orderContent();

        $ress = OrderPay::store($userInfo, $cart);
        $orderId = $ress->id;

        $res = AcceptPay::getPaymentKey($orderId, $cart->total, $userInfo);

        $this->assertIsObject($res);
        $this->assertIsString($res->token);
        $this->assertGreaterThanOrEqual(60, strlen($res->token));
    }

    public function testItCanGetIframeUri()
    {
        $userInfo = factory(Address::class)->create();
        [$p, $items] = $this->createCart(null, 4, [23]);

        $cart = CartSet::instance()->orderContent();

        $ress = OrderPay::store($userInfo, $cart);
        $orderId = $ress->id;

        $uri = AcceptPay::getIframeUri($orderId, $cart->total, $userInfo);

        $this->assertIsString($uri);
        $this->assertStringContainsString(
            AcceptController::DYNAMIC_IFRAME_ID . '?payment_token=',
            $uri
        );
    }

    public function testItCanCreateOrderAndGetIframeUri()
    {
        $userInfo = factory(Address::class)->create();
        [$p, $items] = $this->createCart(null, 4, [23]);

        $cart = CartSet::instance()->orderContent();

        $uri = AcceptPay::createOrder($userInfo, $cart);

        $this->assertIsString($uri);
        $this->assertStringContainsString(
            AcceptController::DYNAMIC_IFRAME_ID . '?payment_token=',
            $uri
        );
        $this->assertNotNull(Session::get('orderRes'));
    }

    public function testItCanValidateTransactionResponse()
    {
        $orderRes = self::getOrderResponse();
        Session::put('orderRes', $orderRes);
        $res = (object) json_decode(json_encode(self::getCompletedRes()), true);
        // dd($res->order, $orderRes->id);
        // $res->order = $orderRes->id;

        $hash = hash_hmac('SHA512', AcceptPay::getHmacStr($res), 'BBD95BE01106CE1CC9E2ED1A427E8CCF');

        $this->assertTrue(AcceptPay::validateTransactionRes($res, $hash));
    }

    public function testItCanCheckHmac()
    {
        $res = self::getCompletedRes();
        $hash = hash_hmac('SHA512', AcceptPay::getHmacStr($res), 'BBD95BE01106CE1CC9E2ED1A427E8CCF');

        $this->assertTrue(AcceptPay::checkHmac($res, $hash));
    }

    public static function getJsonRes(): string
    {
        return file_get_contents(dirname(__DIR__) . '\responses/transactionSuccess.json');
    }

    public static function getOrderResponse(): object
    {
        return json_decode(file_get_contents(dirname(__DIR__) . '\responses/orderRes.json'));
    }

    public static function getTransactionRes(): object
    {
        return json_decode(file_get_contents(dirname(__DIR__) . '\responses/transaction.json'));
    }

    public static function getCompletedRes(): object
    {
        return json_decode(file_get_contents(dirname(__DIR__) . '\responses/completed.json'));
    }
}
