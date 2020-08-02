<?php

namespace Tests\Unit;

use App\DailyDeal;
use App\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DailyDealTest extends TestCase
{
    use RefreshDatabase;

    public function testItHasProducts()
    {
        $p = factory(Product::class)->create();
        $d = factory(DailyDeal::class)->create([
            'product_slug' => $p->slug
        ]);
        $this->assertSame($p->slug, $d->product->slug);
    }
}
