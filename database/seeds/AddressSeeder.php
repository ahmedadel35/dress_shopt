<?php

use App\Address;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        $users = User::all();

        $users->each(function (User $user) {
            $user->addresses()->saveMany(
                factory(Address::class, random_int(0, 3))->make()
            );
        });

        DB::commit();
    }
}
