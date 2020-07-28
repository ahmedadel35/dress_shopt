<?php

use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(TagSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(RateSeeder::class);
        $this->call(CartSeeder::class);
        $this->call(AddressSeeder::class);
        $this->call(CarsoulImagesSeeder::class);
        $this->call(OrderSeeder::class);
    }
}
