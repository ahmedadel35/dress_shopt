<?php

namespace App\Http\Controllers;

use App\Facades\AcceptPay;
use App\Http\Resources\AddressResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Session;

class AcceptOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function create()
    { }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(object $userInfo, object $cart): object
    {
        // check if old orderRes exists THEN return it
        if (session()->has('orderRes')) {
            return session('orderRes');
        }

        $token = AcceptPay::getAuthToken()->getToken();
        $userInfo = json_decode((new AddressResource($userInfo))->toJson());

        $res = Http::post(AcceptController::URI . 'ecommerce/orders', [
            'auth_token' => $token,
            'amount_cents' => $cart->total,
            'currency' => 'EGP',
            'card_integration_id' => AcceptController::CARD_ID,
            'wallet_integration_id' => AcceptController::WALLET_ID,
            // 'merchant_order_id' => $mId,
            'shipping_data' => $userInfo,
            'items' => json_decode($cart->items)
        ]);
        $res = $res->object();
        // Session::put('orderRes', $res);
        session(['orderRes' => $res]);

        // save this product id and total in db
        DB::table('order_ids')
            ->insert([
                'id' => $res->id,
                'total' => $res->amount_cents/100
            ]);
        return $res;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $orderId)
    {
        $token = AcceptPay::getAuthToken()->getToken();
        $res = Http::get(AcceptController::URI . 'ecommerce/orders/' . $orderId, ['auth_token' => $token]);
        // $client = new \GuzzleHttp\Client();
        // $res = $client->request('GET', AcceptController::URI . 'ecommerce/orders/' . $orderId, );

        dd($res->object());

        // return $res->object();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(
        int $orderId,
        object $cart
    ): object {
        $token = AcceptPay::getAuthToken()->getToken();
        $res = Http::put(AcceptController::URI . 'ecommerce/orders/' . $orderId, [
            'auth_token' => $token,
            'amount_cents' => $cart->total,
            'currency' => 'EGP',
            'card_integration_id' => AcceptController::CARD_ID,
            'wallet_integration_id' => AcceptController::WALLET_ID,
            // 'shipping_data' => json_decode($userInfo),
            // 'items' => json_decode($cart->items)
        ]);

        return $res->object();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $orderId)
    {
        $token = AcceptPay::getAuthToken()->getToken();
        $res = Http::delete(AcceptController::URI . 'ecommerce/orders/' . $orderId, [
            'auth_token' => $token,
        ]);

        return $res;
    }
}
