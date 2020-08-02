<?php

namespace Tests\Unit;

use App\Product;
use App\Rate;
use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RateTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testAppendsAtRunTimeWithDiffrentLocales()
    {
        $rate = factory(Rate::class)->create();

        $this->assertIsString($rate->updated);
        app()->setLocale('en');
        $this->assertEquals('1 second ago', $rate->updated);

        session()->put('locale', 'ar');
        $this->assertEquals('منذ ثانية', $rate->updated);
    }

    public function testItBelongsToUser()
    {
        $user = factory(User::class)->create();
        $rate = factory(Rate::class)->create([
            'user_id' => $user->id
        ]);

        $this->assertEquals($user->id, $rate->owner->id);
    }

    public function testItBelongsToProduct()
    {
        $p = factory(Product::class)->create();
        $r = factory(Rate::class)->create([
            'product_id' => $p->id
        ]);

        $this->assertEquals($p->id, $r->product->id);
    }

    public function testItHaveCanUpdateAttribute()
    {
        $r = factory(Rate::class)->create();

        $this->assertIsBool($r->can_update);

        $this->signIn(null, ['role' => User::AdminRole]);

        $this->assertTrue($r->can_update);
    }
}
