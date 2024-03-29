<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

class CarsoulImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        foreach (range(1, 3) as $i) {
            DB::table('carsoul_images')
                ->insert([
                    'img' =>'/default-images//' .  $i . '.jpg',
                    'title' => random_int(0, 1) ? $faker->sentence : '',
                    'url' => Arr::random(['#', ''])
                ]);
        }
    }
}
