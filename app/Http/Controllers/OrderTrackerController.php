<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class OrderTrackerController extends Controller
{
    use GetCategoryList;

    public function tracker()
    {
        return view('order.tracker.index', [
            'order' => null,
            'empty' => null,
            'cats' => $this->getList()
        ]);
    }

    public function getTracked(Request $request)
    {
        $res = (object) $this->validate($request, [
            'orderId' => 'required|string|min:3',
            'email' => 'required|email'
        ], [], [
            'orderId' => __('order.idTxt'),
            'email' => __('auth.E-Mail-Address')
        ]);

        $order = Order::whereId(Order::decId($res->orderId))
            ->whereHas('address', function (Builder $q) use ($res) {
                $q->where('userMail', $res->email);
            })->with('address')
            ->limit(1)
            ->get()->first();

        $empty = $order === null;

        return view('order.tracker.index', compact('order', 'empty') + ['cats' => $this->getList()]);
    }

    public function complete(Request $request, string $order_num)
    {
        $order = Order::findOrFail(Order::decId($order_num));

        $order->status = 'completed';
        $updated = $order->update();

        if ($request->wantsJson()) {
            return response()->json(compact('updated'));
        }
        
        return back()->with('completed', __('order.form.completed'));
    }
}
