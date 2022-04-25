<?php

namespace App\Http\Controllers;

use App\Address;
use App\Order;
use App\Rate;
use App\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Session;
use Vinkla\Hashids\Facades\Hashids;

class UserProfileController extends Controller
{
    use GetCategoryList;

    const IMAGE_DIR = '/public/user_profile';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(?string $userId = null)
    {
        $user = $this->getUser($userId);

        DB::beginTransaction();

        $orders = DB::table('orders')
            ->selectRaw('COUNT(id) as c')
            ->where('user_id', $user->id)
            ->first();

        $completedOrders = DB::table('orders')
            ->selectRaw('COUNT(id) as c')
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->first();

        $processOrders = DB::table('orders')
            ->selectRaw('COUNT(id) as c')
            ->where('user_id', $user->id)
            ->where('status', 'processing')
            ->first();

        $totalPaid = DB::table('orders')
            ->selectRaw('SUM(total) as c')
            ->where('user_id', $user->id)
            ->first();

        $totalItems = DB::table('orders')
            ->selectRaw('SUM(qty) as c')
            ->where('user_id', $user->id)
            ->first();

        $onDelivery = DB::table('orders')
            ->selectRaw('SUM(id) as c')
            ->where('user_id', $user->id)
            ->where('paymentMethod', 'onDelivery')
            ->first();

        $accept = DB::table('orders')
            ->selectRaw('SUM(id) as c')
            ->where('user_id', $user->id)
            ->where('paymentMethod', 'accept')
            ->first();

        $addresses = DB::table('addresses')
            ->selectRaw('COUNT(id) as c')
            ->where('user_id', $user->id)
            ->first();

        $rates = DB::table('rates')
            ->selectRaw('COUNT(id) as c')
            ->where('user_id', $user->id)
            ->first();

        DB::commit();

        $count = compact(
            'orders',
            'completedOrders',
            'processOrders',
            'totalPaid',
            'totalItems',
            'onDelivery',
            'accept',
            'addresses',
            'rates'
        );

        $cats = $this->getList();

        return view('user.dashbored', compact(
            'user',
            'count',
            'cats'
        ));
    }

    public function loadOrders(?string $userId = null)
    {
        $user = $this->getUser($userId);
        $orders = Order::whereUserId($user->id)
            ->with('itp')
            ->with('address')
            ->get();

        $cats = $this->getList();


        return view('user.orders-list', compact('user', 'orders', 'cats'));
    }

    public function addressList(?string $userId = null)
    {
        $user = $this->getUser($userId);
        $addresses = Address::whereUserId($user->id)
            ->get();

        $cats = $this->getList();


        return view('user.addresses', compact('user', 'addresses', 'cats'));
    }

    public function loadProfile(?string $userId = null)
    {
        $user = $this->getUser($userId);
        // $addresses = Address::whereUserId($user->id)
        //     ->get();

        $cats = $this->getList();

        return view('user.profile', compact('user', 'cats'));
    }

    public function updateImg(Request $request)
    {
        app()->setLocale(Session::get('locale'));
        $res = (object) $this->validate($request, [
            'img' => 'required|image|mimes:jpeg,png,jpg|max:1024'
        ], [], [
            'img' => __('user.profile.image')
        ]);

        $user = User::findOrFail(auth()->id());

        $path = $res->img->store(self::IMAGE_DIR);
        $oldImg = $user->img;
        $user->img = Str::replaceFirst("public", "storage", $path);
        $user->update();

        // remove user old image
        if ($oldImg !== 'storage/user_profile/default.jpg') {
            $oldImg = Str::replaceFirst("storage", "public", $oldImg);
            Storage::delete($oldImg);
        }

        return response()->json(['img' => $user->img]);
    }

    public function updatePass(
        Request $request,
        ?string $userId = null
    ) {
        $res = (object) $this->validate($request, [
            'oldpass' => 'required|string',
            'pass' => 'required|string',
            'conf' => 'required|string|same:pass'
        ], [], [
            'oldpass' => __('user.profile.oldPass'),
            'pass' => __('user.profile.pass'),
            'conf' => __('user.profile.conf'),
        ]);

        $user = User::findOrFail($this->getUser()->id);

        if (!(Hash::check($res->oldpass, $user->password))) {
            return back()->with('error', __('user.profile.passnotright'));
        }

        $user->fill([
            'password' => Hash::make($res->pass)
        ])->save();

        return back()->with('success', __('user.profile.updatedPass'));
    }

    public function loadRates(?string $userId = null)
    {
        $user = $this->getUser($userId);

        $rates = Rate::whereUserId($user->id)
            ->with('product')
            ->paginate(15);

        return view('user.rates', [
            'cats' => $this->getList(),
            'rates' => $rates,
            'user' => $user
        ]);
    }

    private function getUser(?string $userId = null): User
    {
        if (is_null($userId)) {
            return auth()->user();
        }

        return User::findOrFail(Hashids::decode($userId)[0]);
    }
}
