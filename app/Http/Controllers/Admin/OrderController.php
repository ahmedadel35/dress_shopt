<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GetCategoryList;
use App\Order;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class OrderController extends Controller
{
    use GetCategoryList;

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $cats = $this->getList();

        $orders = Order::paginate();

        return view('admin.orders', compact('user', 'cats', 'orders'));
    }

    public function complete(Request $request, string $enc_id) {
        $res = (object) request()->validate([
            'done' => 'sometimes|boolean'
        ]);

        $order = Order::findOrFail(Order::decId($enc_id));

        $order->status = (bool) $request->get('done', false) ? 'completed' : 'declined';

        return response()->json([
            'updated' => $order->update()
        ]);
    }
}
