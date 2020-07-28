<?php

namespace App\Http\Controllers;

use App\Facades\OrderPay;
use App\Http\Resources\AddressResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Session;
use Tests\Feature\AcceptControllerTest;

class AcceptController extends Controller
{
    private const HMAC_SECRET = 'BBD95BE01106CE1CC9E2ED1A427E8CCF';
    const URI = 'https://accept.paymobsolutions.com/api/';
    const CARD_ID = 19466;
    const WALLET_ID = 21925;
    const DYNAMIC_IFRAME_ID = 31738;

    private $apiKey;
    private $token;
    private $merchantId;

    public function __construct()
    {
        $this->apiKey = config('app.apiKey');
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getMId(): int
    {
        return $this->merchantId;
    }

    public function getAuthToken(): self
    {
        $res = Http::post(self::URI . 'auth/tokens', [
            'api_key' => $this->apiKey
        ]);

        $res = $res->object();
        $this->token = $res->token;
        $this->merchantId = $res->profile->id;

        return $this;
    }

    public function getPaymentKey(int $orderId, int $total, object $userInfo): object
    {
        $token = $this->getAuthToken()->getToken();
        $userInfo = json_decode((new AddressResource($userInfo))->toJson());

        $res = Http::post(self::URI . 'acceptance/payment_keys', [
            'auth_token' => $token,
            'order_id' => $orderId,
            'card_integration_id' => self::CARD_ID,
            "currency" => "EGP",
            'lock_order_when_paid' => true,
            'amount_cents' => $total,
            'billing_data' => $userInfo
        ]);

        return $res->object();
    }

    public function getIframeUri(int $orderId, int $total, object $userInfo): string
    {
        $res = $this->getPaymentKey($orderId, $total, $userInfo);
        $paymentKey = $res->token;

        return self::URI . 'acceptance/iframes/' . self::DYNAMIC_IFRAME_ID . '?payment_token=' . $paymentKey;
    }

    public function createOrder(object $userInfo, object $cart): string
    {
        $res = OrderPay::store($userInfo, $cart);
        
        if (!isset($res->id)) {
            dd($res);
        }

        $uri = $this->getIframeUri($res->id, $cart->total, $userInfo);

        return $uri;
    }

    public function validateTransactionRes(object $res, string $hmac = ''): bool
    {
        $orderRes = Session::get('orderRes');

        if (
            $orderRes->id === (int) $res->order &&
            $orderRes->amount_cents === (int) $res->amount_cents &&
            $orderRes->currency === $res->currency &&
            (int) $res->integration_id === self::CARD_ID
        ) {
            return $this->checkHmac($res, $hmac);
            // return true;
        }

        return false;
    }

    public function checkHmac(object $res, string $hamc, bool $proccess = false)
    {
        $str = $proccess ? $this->getHmacStrProccessed($res) :  $this->getHmacStr($res);

        // dump(hash_hmac('SHA512', $str, self::HMAC_SECRET));

        return hash_hmac('SHA512', $str, self::HMAC_SECRET) === $hamc;
    }

    public function getHmacStr(object $res): string
    {
        return $res->amount_cents . $res->created_at . $res->currency . $res->error_occured . $res->has_parent_transaction . $res->id . $res->integration_id . $res->is_3d_secure . $res->is_auth . $res->is_capture . $res->is_refunded . $res->is_standalone_payment . $res->is_voided . $res->order . $res->owner . $res->pending . $res->source_data_pan . $res->source_data_sub_type . $res->source_data_type . $res->success;
    }

    public function getHmacStrProccessed(object $res)
    {
        return $res->amount_cents . $res->created_at . $res->currency . var_export($res->error_occured, true) . var_export($res->has_parent_transaction, true) . $res->id . $res->integration_id . var_export($res->is_3d_secure, true) . var_export($res->is_auth, true) . var_export($res->is_capture, true) . var_export($res->is_refunded, true) . var_export($res->is_standalone_payment, true) . var_export($res->is_voided, true) . $res->order['id'] . $res->owner . var_export($res->pending, true) . $res->source_data['pan'] . $res->source_data['sub_type'] . $res->source_data['type'] . var_export($res->success, true);
    }
}
