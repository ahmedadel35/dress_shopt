<?php

namespace Tests\Unit;

use App\Category;
use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testItHasSubCategories()
    {
        $cat = factory(Category::class)->create();

        $cat->sub_cats()->save(factory(Category::class)->create([
            'category_id' => $cat->id
        ]));

        $this->assertCount(1, $cat->sub_cats);
    }

    public function testItHasSlug()
    {
        $title = $this->faker->sentence;

        $cat = factory(Category::class)->create(compact('title'));

        $this->assertSame(
            Str::slug(Str::lower($title)),
            $cat->slug
        );
    }

    public function testItCanHaveManyProducts()
    {
        $cat = factory(Category::class)->create();

        $cat->products()->saveMany(
            factory(Product::class, 5)->make()
        );

        $cat = Category::first();

        $this->assertIsIterable($cat->products);
        $this->assertCount(5, $cat->products);
    }
}
