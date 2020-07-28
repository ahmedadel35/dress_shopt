<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        User::all()->each(function (User $user) {
            $user->cart()->create();
            $user->wishlist()->create([
                'instance' => 'wish'
            ]);
        });

        DB::commit();
    }
}
