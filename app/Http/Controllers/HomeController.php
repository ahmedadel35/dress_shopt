<?php

namespace App\Http\Controllers;

use App\Facades\AcceptPay;
use App\Facades\OrderPay;
use App\Product;
use App\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    use GetCategoryList;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $carsoulImages = DB::table('carsoul_images')
            ->get(['img', 'title', 'url']);

        return view('home', [
            'cats' => $this->getList(),
            'carImgs' => $carsoulImages
        ]);
    }

    public function data()
    {
        $data = Product::with('rates')
            ->paginate();
        return response()->json($data);
    }

    public function latestProducts()
    {
        return response()->json(
            Product::select('*')
                ->latest()
                ->limit(12)
                ->get()
        );
    }

    public function contact()
    {
        return view('contact', [
            'cats' => $this->getList()
        ]);
    }

    public function showSome()
    {
        // $res = OrderPay::store();

        // $token = $res->token;

        // var_dump("location: https://accept.paymobsolutions.com/api/acceptance/iframes/31738?payment_token=$token");

    }
}
