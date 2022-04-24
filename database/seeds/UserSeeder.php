<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        factory(User::class)->create([
            'email' => 'admin@site.test',
            'role' => User::AdminRole
        ]);

        factory(User::class)->create([
            'email' => 'super@site.test',
            'role' => User::SuperRole
        ]);

        factory(User::class)->create([
            'email' => 'delivery@site.test',
            'role' => User::DeliveryRole
        ]);

        factory(User::class)->create([
            'email' => 'user@site.test'
        ]);

        factory(User::class, 10)->create();

        DB::commit();
    }
}
