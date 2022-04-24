<?php

use App\Order;
use App\OrderItem;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::beginTransaction();
        // $users = User::all();

        // $users->each(function (User $user) {
        //     $user->orders()->saveMany(
        //         factory(Order::class, random_int(2, 5))->make()
        //     );
        // });

        // $orders = Order::all();

        // $orders->each(function (Order $order) {
        //     $order->items()->saveMany(
        //         factory(OrderItem::class, random_int(3, 7))->make()
        //     );
        // });


        // DB::commit();
    }
}
