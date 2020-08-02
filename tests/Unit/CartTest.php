<?php

namespace Tests\Unit;

use App\Cart;
use App\CartItem;
use App\Product;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testItHasOwner()
    {
        $user = factory(User::class)->create();
        $c = factory(Cart::class)->create([
            'user_id' => $user->id
        ]);

        $this->assertEquals($user->id, $c->owner->id);
    }

    public function testItHasManyCartItems()
    {
        $c = factory(Cart::class)->create();
        $items = $c->items()->saveMany(
            factory(CartItem::class, 5)->make()
        );

        $this->assertCount(5, $c->items);
    }

    public function testItHaveCoun()
    {
        $c = factory(Cart::class)->create();
        $items = $c->items()->saveMany(
            factory(CartItem::class, 5)->make([
                'qty' => 4
            ])
        );

        $this->assertEquals(20, $c->count);
    }
}
