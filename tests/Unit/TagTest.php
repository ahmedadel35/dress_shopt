<?php

namespace Tests\Unit;

use App\Product;
use App\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class TagTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCreatingTagWillTurnTitleIntoSlugString()
    {
        $title = $this->faker->words(3, true);
        $tag = Tag::create(compact('title'));
        $tag2 = Tag::create(compact('title'));

        $this->assertEquals(
            Str::slug($title),
            $tag->slug
        );
    }

    public function testTagHasProducts()
    {
        $tag = factory(Tag::class)->create();
        $prods = factory(Product::class, 5)->create();
        $tag->products()->sync($prods->pluck('id'));

        $this->assertCount(5, $tag->products);
    }
}
