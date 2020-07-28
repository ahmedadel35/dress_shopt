<?php

use App\Product;
use App\Rate;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class RateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        $users_ids = ((User::all())->pluck('id'))->toArray();
        $randId = function() use ($users_ids) {
            return Arr::random($users_ids);
        };
        (Product::all())->each(function (Product $p) use ($randId){
            $p->rates()->saveMany(
                factory(Rate::class, random_int(3, 7))->make([
                    'user_id' => $randId
                ])
            );
        });

        DB::commit();
    }
}
