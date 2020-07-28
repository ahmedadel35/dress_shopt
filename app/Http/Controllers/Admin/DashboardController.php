<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $user = auth()->user();

        $products = DB::table('products')
            ->selectRaw('COUNT(id) as c')
            ->first();

        $stillStock = DB::table('products')
            ->selectRaw('COUNT(id) as c')
            ->where('qty', '>', '1')
            ->first();

        $outOfStock = DB::table('products')
            ->selectRaw('COUNT(id) as c')
            ->where('qty', '<', '1')
            ->first();

        $orders = DB::table('orders')
            ->selectRaw('COUNT(id) as c')
            ->first();

        $totalPaid = DB::table('orders')
            ->selectRaw('SUM(total) as c')
            ->first();

        $totalItems = DB::table('orders')
            ->selectRaw('SUM(qty) as c')
            ->first();

        $completedOrders = DB::table('orders')
            ->selectRaw('COUNT(id) as c')
            ->where('status', 'completed')
            ->first();

        $processOrders = DB::table('orders')
            ->selectRaw('COUNT(id) as c')
            ->where('status', 'processing')
            ->first();

        $onDelivery = DB::table('orders')
            ->selectRaw('SUM(id) as c')
            ->where('paymentMethod', 'onDelivery')
            ->first();

        $accept = DB::table('orders')
            ->selectRaw('SUM(id) as c')
            ->where('paymentMethod', 'accept')
            ->first();

        $addresses = DB::table('addresses')
            ->selectRaw('COUNT(id) as c')
            ->first();

        $rates = DB::table('rates')
            ->selectRaw('COUNT(id) as c')
            ->first();

        $parentCat = DB::table('categories')
            ->selectRaw('COUNT(id) as c')
            ->whereNull('category_id')
            ->first();

        $subCats = DB::table('categories')
            ->selectRaw('COUNT(id) as c')
            ->whereNotNull('category_id')
            ->first();

        // $daily = DB::table('daily_deals')
        //     ->selectRaw('COUNT(id) as c')
        //     ->first();

        $tags = DB::table('tags')
            ->selectRaw('COUNT(slug) as c')
            ->first();

        $banners = DB::table('carsoul_images')
            ->selectRaw('COUNT(id) as c')
            ->first();

        DB::commit();

        $count = compact(
            'products',
            'stillStock',
            'outOfStock',
            'orders',
            'totalPaid',
            'totalItems',
            'completedOrders',
            'processOrders',
            'onDelivery',
            'accept',
            'addresses',
            'rates',
            'parentCat',
            'subCats',
            // 'daily',
            'tags',
            'banners'
        );

        return view('admin.dash', compact('user', 'count'));
    }
}
