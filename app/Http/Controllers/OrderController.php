<?php

namespace App\Http\Controllers;

use AcceptPay;
use App\Address;
use App\Mail\OrderProccessing;
use App\Order;
use CartSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Mail;
use Session;
use Tests\Feature\AcceptControllerTest;
use Vinkla\Hashids\Facades\Hashids;

class OrderController extends Controller
{
    use GetCategoryList;

    const VALIDATE_ARR = [
        'userMail' => 'required|email',
        'total' => 'required|numeric|min:1',
        'qty' => 'required|numeric|min:1',
        'paymentMethod' => 'required|alpha|min:4|max:10',
        'items' => 'required|array',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.qty' => 'required|min:1'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // regenrate session id
        session()->regenerate();

        abort_unless(CartSet::instance()->content()->count(), 404);

        return view('order.index', [
            'cats' => $this->getList()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $res = (object) request()->validate(self::VALIDATE_ARR);

        if (request()->get('check', false)) {
            return response()->json($res);
        }

        abort_if($res->paymentMethod === 'accept', 403);

        $res->address_id = request()->validate([
            'address_id' => 'required|int|exists:addresses,id',
        ])['address_id'];

        $cart = CartSet::instance()->contentResource(false);

        $order = Order::createNew([
            'user_id' => auth()->id(),
            'address_id' => $res->address_id,
            'total' => $cart->total,
            'qty' => $cart->count,
            'paymentStatus' => true
        ]);

        // save order items
        $order->addItems($cart->items->toArray());

        // clear cart
        CartSet::instance()->destroy();

        // send mail
        Mail::to($res->userMail)
            ->locale(session()->get('locale', 'ar'))
            ->send(new OrderProccessing($order, $res->userMail));

        return response()->json($order->toArray());
    }

    public function iframeUri()
    {
        $res = (object) request()->validate(self::VALIDATE_ARR + [
            'address.firstName' => 'required|string',
            'address.lastName' => 'required|string',
            'address.address' => 'required|string',
            'address.dep' => 'nullable',
            'address.city' => 'required|string',
            'address.country' => 'required|string',
            'address.gov' => 'required|string',
            'address.postCode' => 'required',
            'address.phoneNumber' => 'required',
            'address.notes' => 'nullable',
            'address.userMail' => 'required|email'
        ]);

        $cart = CartSet::instance()->orderContent();
        $userInfo = new Address((array) $res->address);

        $uri = AcceptPay::createOrder($userInfo, $cart);

        return response()->json(['uri' => $uri]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return response()->json($order);
    }

    public function processed(Request $request)
    {
        $res = $request->all();
        $obj = (object) $res['obj'];

        abort_unless(
            $request->has('hmac') &&
                AcceptPay::checkHmac($obj, $res['hmac'], true)
                && $obj->success
                && !$obj->error_occured,
            404
        );

        try {
            request()->validate([
                'type' => 'required|alpha',
                'obj.id' => 'required|int|min:1',
                'obj.amount_cents' => 'required|int|min:1',
                'obj.order.id' => 'required|int|min:1|exists:order_ids,id',
                // TODO add |accepted
                'obj.is_live' => 'required|boolean',
                'obj.error_occured' => 'required|boolean',
                'obj.integration_id' => 'required|int|in:' .  AcceptController::CARD_ID,
                'obj.currency' => "required|in:EGP",
                'obj.order.items' => 'required|array',
                'obj.order.shipping_data' => 'required',
                'obj.order.shipping_data.id' => 'required|int|min:1'
            ]);
        } catch (ValidationException $e) {
            abort(404);
            // dd($e->getMessage());
        }

        // check if order id and total price is same as local price
        $found = DB::table('order_ids')
            ->selectRaw("COUNT('id') as cid")
            ->where('id', $obj->order['id'])
            ->where('total', $obj->amount_cents / 100)
            ->get('cid')->first()->cid;

        abort_unless((bool) (int) $found, 404);


        $qty = 0;
        foreach ($obj->order['items'] as $item) {
            $qty += $item['quantity'];
        }

        // save address from shipping data
        $ship = (object) $obj->order['shipping_data'];
        $address = Address::create([
            'firstName' => $ship->first_name,
            'lastName' => $ship->last_name,
            'address' => $ship->street,
            'dep' => $ship->building,
            'city' => $ship->city,
            'country' => $ship->country,
            'gov' => $ship->state,
            'userMail' => $ship->email,
            'phoneNumber' => $ship->phone_number,
            'postCode' => $ship->postal_code,
            'notes' => $ship->extra_description
        ]);

        // save order
        $order = Order::create([
            'orderNum' => $obj->order['id'],
            'user_id' => auth()->id(),
            'address_id' => $address->id,
            'total' => $obj->amount_cents / 100,
            'qty' => $qty,
            'paymentMethod' => 'accept',
            'paymentStatus' => true,
            'status' => $obj->pending ? 'pending' : 'processing',
            'transaction_id' => $obj->id,
            'created_at' => $obj->created_at,
            'updated_at' => $obj->created_at
        ]);

        // save order items
        $order->addItems($obj->order['items'], true);

        // delete saved order id
        DB::table('order_ids')
            ->delete($obj->order['id']);

        // send mail
        Mail::to($ship->email)
            ->locale(session()->get('locale', 'ar'))
            ->send(new OrderProccessing($order, $ship->email));

        // return response()->json($order->toArray());
        return response()->noContent();
    }

    public function completed()
    {
        $obj = (object) request()->all();
        $err = false;

        // dd($obj->success);
        // dd(AcceptPay::validateTransactionRes($obj, $obj->hmac));

        abort_unless(AcceptPay::validateTransactionRes($obj, $obj->hmac), 404);

        $orderRes = Session::get('orderRes');
        $orderId = 0;
        $err = $obj->txn_response_code;

        if ($obj->success === 'true') {
            // TODO check if order with this order id was saved
            $order = (object) (Order::where('orderNum', $orderRes->id)
                ->get('orderNum')
                ->first())->toArray();

            abort_unless((bool) $order->enc_id, 404);
            $orderId = $order->enc_id;
            $err = false;
            // clear cart
            CartSet::instance()->destroy();

            // destroy orderRes session
            Session::put('orderRes', null);

            // update order with user id
            Order::update(['user_id' => auth()->id()])
                ->where('orderNum', $orderRes->id);
        }

        $obj->data_message = str_replace(' ', '_', $obj->data_message);

        return view('payment.completed', compact('err', 'obj', 'orderId') + ['cats' => $this->getList()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $res = request()->validate([
            'paymentMethod' => 'required|string'
        ]);

        $order->paymentMethod = $res['paymentMethod'];
        $updated = $order->update();

        return response()->json(compact('updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
