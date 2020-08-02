<?php

namespace Tests\Feature;

use App\Address;
use App\CartItem;
use App\Http\Resources\CartItemOrderCollection;
use App\Http\Resources\CartItemOrderResource;
use App\Mail\OrderProccessing;
use App\Product;
use CartSet;
use DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mail;
use Tests\TestCase;
use Vinkla\Hashids\Facades\Hashids;

class OrderProccessTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testItWillNotSaveOrderWithInvalidData()
    {
        // $this->withoutExceptionHandling();
        $res = AcceptControllerTest::getTransactionRes();

        $res->obj->success = false;
        $res->obj->error_occured = null;
        $res->obj->amount_cents = 0;
        $res = json_decode(json_encode($res), true);

        $this->post('/userpayment/proccessor/order', $res)
            ->assertStatus(404);

        $res['hmac'] = 'wcasdasd';

        $this->post('/userpayment/proccessor/order', $res)
            ->assertStatus(404);
    }

    public function testItWillNotSaveOrderIfIdNotSavedInDB()
    {
        $res = AcceptControllerTest::getTransactionRes();
        $res = json_decode(json_encode($res), true);

        $this->post('/userpayment/proccessor/order', $res)
            ->assertStatus(404);
    }

    public function testItWillNotSaveIfOrderTotalIsInvalid()
    {
        // $this->withoutExceptionHandling();
        $res = AcceptControllerTest::getTransactionRes();
        DB::table('order_ids')
            ->insert([
                'id' => $res->obj->order->id,
                'total' => $res->obj->amount_cents / 100 + 25
            ]);

        $res = json_decode(json_encode($res), true);
        $this->post('/userpayment/proccessor/order', $res)
            ->assertStatus(404);
    }

    public function testItWillSaveOrder()
    {
        $this->withoutExceptionHandling();

        Mail::fake();
        Mail::assertNothingQueued();

        $items = factory(CartItem::class, 5)->create([
            'qty' => 25,
            'size' => random_int(0, 2),
            'color' => random_int(0, 2),
        ]);
        $res = AcceptControllerTest::getTransactionRes();
        $res->obj->order->items = json_decode((new CartItemOrderCollection($items))->toJson(), true);
        $obj = $res->obj;
        $orderId = $obj->order->id;
        $orderTotal = $obj->amount_cents / 100;

        DB::table('order_ids')
            ->insert([
                'id' => $orderId,
                'total' => $orderTotal
            ]);

        $res = json_decode(json_encode($res), true);
        $this->post('/userpayment/proccessor/order', $res)
            ->assertNoContent();

        $ship =  $obj->order->shipping_data;
        $this->assertDatabaseHas('addresses', [
            'firstName' => $ship->first_name,
            'lastName' => $ship->last_name,
            'userMail' => $ship->email,
            'phoneNumber' => $ship->phone_number,
            'gov' => $ship->state
        ]);

        $address = Address::latest()->first();

        $this->assertDatabaseHas('orders', [
            'orderNum' => $orderId,
            'total' => $orderTotal,
            'address_id' => $address->id,
            'transaction_id' => $obj->id,
            'status' => 'processing'
        ]);

        $this->assertDatabaseMissing('order_ids', [
            'id' => $orderId,
            'total' => $orderTotal,
        ]);

        Mail::assertQueued(OrderProccessing::class, function (OrderProccessing $mail) use ($obj) {
            return $mail->hasTo($obj->order->shipping_data->email);
        });
    }

    public function testItWillSaveOrderCartItems()
    {
        $this->testItWillSaveOrder();

        $this->assertDatabaseCount('order_items', 5);
        $this->assertDatabaseHas('order_items', [
            'product_id' => 1,
            'order_id' => 1,
            'qty' => 25
        ]);
        $this->assertDatabaseHas('order_items', [
            'product_id' => 3,
            'order_id' => 1,
            'qty' => 25
        ]);
    }

    public function testCompleteItWillNotOpenIfValidationFailed()
    {
        // $this->withoutExceptionHandling();
        $orderRes = json_decode(json_encode(AcceptControllerTest::getOrderResponse()));
        session(['orderRes' => $orderRes]);
        $query = AcceptControllerTest::getCompletedRes();
        $query->hmac = 'dsasdasd';

        $query = json_decode(json_encode($query), true);

        $uri = '/en/order/payment/completed?';
        $uri .= http_build_query($query);

        $this->get($uri)
            ->assertNotFound();
    }

    /**
     * @dataProvider willShowData
     *
     * @param array $data
     * @return void
     */
    public function testItWillShowErrorsIfNotSaved(int $txn)
    {
        $msg = __('payment.errorProcessing');
        switch ($txn) {
            case 1:
                $msg = __('payment.errorProcessing');
                break;
            case 2:
                $msg = __('payment.cardBank');
                break;
            case 4:
                $msg = __('payment.expired');
                break;
            case 5:
                $msg = __('payment.insufficient');
                break;
            case 6:
                $msg = __('payment.aleradyproccessed');
                break;
        }

        $orderRes = json_decode(json_encode(AcceptControllerTest::getOrderResponse()));
        session(['orderRes' => $orderRes]);

        $this->testItWillSaveOrder();

        $query = AcceptControllerTest::getCompletedRes();
        $query->success = "false";
        $query->hmac = "a3754aa0dc1b51dc9e7824ec7aff59011486db5b39600dfa653c4e1572c4c78fdb85ea90c9b5cb3e2987c4210a1cf3256b40c844a1c3a2a170bf56713e375df1";
        $query->txn_response_code = $txn;

        $query = json_decode(json_encode($query), true);

        $uri = '/en/order/payment/completed?';
        $uri .= http_build_query($query);

        $this->get($uri)
            ->assertOk()
            ->assertViewIs('payment.completed')
            ->assertViewHas('err')
            ->assertSee('alert alert-danger')
            ->assertSee($msg)
            ->assertDontSee('alert alert-success')
            ->assertDontSee(Hashids::encode($orderRes->id));
    }

    public function testItWillShowSuccessIfNoError()
    {
        $orderRes = json_decode(json_encode(AcceptControllerTest::getOrderResponse()));
        session(['orderRes' => $orderRes]);

        $this->testItWillSaveOrder();

        $query = AcceptControllerTest::getCompletedRes();

        $query = json_decode(json_encode($query), true);

        $uri = '/en/order/payment/completed?';
        $uri .= http_build_query($query);

        $this->get($uri)
            ->assertOk()
            ->assertViewIs('payment.completed')
            ->assertViewHas('orderId', Hashids::encode($orderRes->id))
            ->assertSee('alert alert-success')
            ->assertSee(__('order.yoursId'))
            ->assertSee(__('payment.completed'))
            ->assertSee(Hashids::encode($orderRes->id));
    }

    public function testItWillClearCartIfSuccess()
    {
        $this->createCart(null, 5);
        $this->testItWillShowSuccessIfNoError();
        
        $this->assertCount(0, CartSet::instance()->content());
    }

    /**
     * @dataProvider willShowData
     *
     * @return void
     */
    public function testItWillShowErrorsForUser(int $txn)
    {
        $this->signIn();
        $this->testItWillShowErrorsIfNotSaved($txn);
    }

    public function testItWillShowSuccessForUser()
    {
        $this->signIn();
        $this->testItWillShowSuccessIfNoError();
    }

    public function testItWillRemoveOrderResSession()
    {
        $this->testItWillClearCartIfSuccess();
        $this->assertNull(session('orderRes'));
    }

    public function willShowData(): array
    {
        return [
            [1],
            [2],
            [4],
            [5],
            [6]
        ];
    }
}
