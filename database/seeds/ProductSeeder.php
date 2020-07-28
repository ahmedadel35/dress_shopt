<?php

use App\Category;
use App\Product;
use App\ProductInfo;
use App\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        $cats = Category::whereNotNull('category_id')->get();
        $tags = Tag::all();

        $cats->each(function (Category $c) use ($tags) {
            $products = factory(Product::class, mt_rand(15, 35))
                ->create([
                    'category_slug' => $c->slug,
                    'qty' => random_int(0, 563),
                ]);
            $products->each(function (Product $p) use ($tags) {
                foreach (range(1, 3) as $i) {
                    $p->tags()->attach($tags->random());
                    $p->pi()->save(
                        factory(ProductInfo::class)->make()
                    );
                }
            });
            // User::find(3)->products()->saveMany(
            //     factory(Product::class, mt_rand(15, 35))->make([
            //         'category_slug' => $c->slug,
            //     ])
            // );
        });

        DB::commit();
    }
}
