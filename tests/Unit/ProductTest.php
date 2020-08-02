<?php

namespace Tests\Unit;

use App\Category;
use App\DailyDeal;
use App\Product;
use App\ProductInfo;
use App\Rate;
use App\Tag;
use App\User;
use App\Cart;
use CartSet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testItHaveCasts()
    {
        $p = factory(Product::class)->create();
        $this->assertIsFloat($p->price);
        $this->assertIsFloat($p->save);
        $this->assertIsArray($p->colors);
        $this->assertIsArray($p->sizes);
        $this->assertIsArray($p->images);
    }

    public function testItOwnedToUser()
    {
        $u = factory(User::class)->create();
        $p = factory(Product::class)->create(['user_id' => $u->id]);

        $this->assertEquals($u->id, $p->owner->id);
    }

    public function testGetProductImagePath()
    {
        $p = factory(Product::class)->create();

        $this->assertEquals(
            $p->images[0],
            $p->img_path . $p->images[0]
        );
    }

    public function testItHasSlug()
    {
        $title = $this->faker->sentence;
        $p = factory(Product::class)->create(compact('title'));

        $this->assertEquals(
            Str::slug($title),
            $p->slug
        );
    }

    public function testItBelongsToCategory()
    {
        $title = $this->faker->sentence;
        $c = factory(Category::class)->create(compact('title'));
        $p = factory(Product::class)->create([
            'category_slug' => $c->slug
        ]);

        $this->assertIsNotIterable($p->category);
        $this->assertEquals(
            Str::slug($title),
            $p->category->slug
        );
    }

    public function testItCanHaveTags()
    {
        $p = factory(Product::class)->create();

        $tags = $p->tags()->saveMany(
            factory(Tag::class, 5)->make()
        );

        $this->assertIsIterable($p->tags);
        $this->assertEquals(
            $p->id,
            $p->tags->first()->pivot->product_id
        );
    }

    public function testItCanCalculatePriceAfterSaving()
    {
        $p = factory(Product::class)->create();

        $save = round($p->price - ($p->save / 100 * $p->price), 2);

        $this->assertSame($save, $p->savedPrice);
    }

    public function testRntTimeAttributesAppendedToJsonOrArray()
    {
        $p = factory(Product::class)->create();

        $this->assertArrayHasKey('saved_price', $p->toArray());
    }

    public function testItHasInfo()
    {
        $p = factory(Product::class)->create();

        $info = factory(ProductInfo::class)->raw();

        $p->pi()->create($info);

        $p = Product::first();

        $this->assertIsArray($p->pi->more);
        $this->assertSame($info['more'], $p->pi->more);
    }

    public function testProductInfoHasMini()
    {
        $p = factory(Product::class)->create();
        $p->pi()->save(
            factory(ProductInfo::class)->make()
        );

        $p->load('pi');

        $this->assertIsArray($p->pi->mini);
        $this->assertCount(4, $p->pi->mini);
    }

    public function testItHaveDaily()
    {
        $p = factory(Product::class)->create();
        $p->daily()->saveMany(
            factory(DailyDeal::class, 5)->make()
        );

        $this->assertIsIterable($p->daily);
        $this->assertCount(5, $p->daily);
    }

    public function testItHaveCartResource()
    {
        /** @var \App\Product $p */
        $p = factory(Product::class)->create();

        $arr = [
            'user_id' => $p->user_id,
            'price' => $p->price,
            'save' => $p->save,
            'image' => $p->images[0],
            'qty' => $p->qty
        ];

        $this->assertEquals($arr, $p->cart_res);
    }

    public function testItHaveRates()
    {
        $p = factory(Product::class)->create();
        $p->rates()->saveMany(
            factory(Rate::class, 5)->make()
        );

        $this->assertCount(5, $p->rates);
        $this->assertGreaterThanOrEqual(1, $p->rates->avg('rate'));
    }

    public function testItHasRateAvearge()
    {
        $p = factory(Product::class)->create();
        $p->rates()->saveMany(
            factory(Rate::class, 5)->make()
        );

        $this->assertEquals(
            round($p->rates->avg('rate'), 1),
            $p->rate_avg
        );
    }

    public function testItHasAddToCart()
    {
        $p = factory(Product::class)->create();

        $item = $p->addToCart(5);

        $this->assertEquals(5, $item->qty);
        $this->assertEquals($p->id, $item->product_id);

        $item = CartSet::get($item->id);

        $this->assertEquals($p->id, $item->product_id);
        $this->assertNull(CartSet::instance('wish')->get($item->id)->id);
    }

    public function testItCanBeAddedToWishlist()
    {
        $p = factory(Product::class)->create();

        $item = $p->addToCart(4, null, null, true);
        $this->assertEquals(4, $item->qty);

        $this->assertEquals(
            $p->id,
            CartSet::instance('wish')->get($item->id)->product_id
        );
        $this->assertCount(0, CartSet::instance()->content());
    }
}
