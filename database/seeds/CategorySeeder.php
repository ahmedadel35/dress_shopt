<?php

use App\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        $catsArr = [
            'men',
            'women',
            'kids',
            'old'
        ];

        foreach (range(0, 3) as $i) {
            $c = Category::create([
                'category_id' => null,
                'title' => $catsArr[$i]
            ]);

            $c->sub_cats()->createMany(
                factory(Category::class, mt_rand(2, 5))->raw([
                    'category_id' => $c->id
                ])
            );
        }

        DB::commit();
    }
}
