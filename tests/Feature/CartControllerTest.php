<?php

namespace Tests\Feature;

use App\Cart;
use App\CartItem;
use App\Product;
use App\Rate;
use App\User;
use Arr;
use DB;
use CartSet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testGuestCanAddProductToCartSession()
    {
        $this->withoutExceptionHandling();
        $p = factory(Product::class)->create();
        $p->rates()->saveMany(
            factory(Rate::class, 5)->make()
        );
        $qty = random_int(1, $p->qty);

        $item = $this->post('/cart/' . $p->slug, [
            'qty' => $qty,
            'size' => 1,
            'color' => 0
        ])->assertOk()
            ->assertJsonPath('item.product_id', $p->id)
            ->assertJsonPath('item.qty', $qty);

        $this->assertDatabaseMissing(
            'cart_items',
            ['product_id' => $p->id]
        );
        $this->assertDatabaseMissing(
            'carts',
            ['user_id' => 1]
        );

        $res = json_decode($item->getContent());

        $this->assertEquals(
            $res->item->product_id,
            $p->id
        );
        $this->assertEquals(
            $res->item->product->slug,
            $p->slug
        );
    }

    public function testUserCanAddProudctToCartDatabaseWithUserId()
    {
        $this->withoutExceptionHandling();
        $user = $this->signIn();

        $p = factory(Product::class)->create();
        $qty = random_int(1, $p->qty);

        $item = $this->post('/cart/' . $p->slug, [
            'qty' => $qty,
            'size' => 1,
            'color' => 0
        ])->assertOk()
            ->assertJsonPath('item.product_id', $p->id)
            ->assertJsonPath('item.product.slug', $p->slug);

        $this->assertDatabaseHas('carts', ['user_id' => $user->id]);
        $this->assertDatabaseHas('cart_items', ['product_id' => $p->id]);
    }

    public function testAnyOneCanNotAddSameProductToCartTwice()
    {
        $this->withoutExceptionHandling();

        [$p, $item] = $this->createCart();
        $this->signIn();
        $qty = random_int(1, $p->qty);

        $item = $this->post('/cart/' . $p->slug, [
            'qty' => $qty,
            'size' => 1,
            'color' => 0
        ])->assertOk()
            ->assertExactJson(['exists' => true]);
    }

    public function testUserCanUseAddedCartBefortAuthAfterLoggingIn()
    {
        // $this->withoutExceptionHandling();

        [$p, $items] = $this->createCart(null, 5, null, true);
        [$cartProduct, $cartItems] = $this->createCart(null, 5);

        $item = $items->last();
        unset($item->product);
        $this->assertDatabaseMissing('cart_items', [
            'product_id' => $p->id
        ]);

        $user = $this->signIn();

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
        ]);
        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'instance' => 'wish'
        ]);

        $items = CartItem::whereInstance('wish')->get();
        $cartItems = CartItem::whereInstance(null)->get();

        // dump($items->last()->toArray());

        $this->assertEquals(
            CartSet::instance('wish')->get($items->last()->id)->product_id,
            $p->id
        );
        $this->assertEquals(
            $p->slug,
            CartSet::instance('wish')->get($items->last()->id)->product->slug
        );
        $this->assertEquals(
            CartSet::instance()->get($cartItems->last()->id)->product_id,
            $cartProduct->id
        );
        session()->invalidate();

        $this->postJson('/cart/' . $p->slug, [
            'qty' => 60,
            'color' => 1,
            'size' => 2,
        ])->assertOk();

        $count = CartItem::count();

        $this->signIn($user);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $p->id,
            'qty' => 60,
            'size' => 2,
            'color' => 1
        ]);

        $this->assertEquals($count, CartItem::count());
    }

    public function testAnyOneCanNotAddCartWithInvalidData()
    {
        $p = factory(Product::class)->create();

        $this->postJson('/cart/' . $p->slug, [
            'color' => -1
        ])
            ->assertStatus(422)
            ->assertJsonPath('errors.qty', ['The qty field is required.'])
            ->assertJsonPath('errors.color', ["The color must be at least 0."]);

        $sizesLen = sizeof($p->sizes);

        $this->postJson('/cart/' . $p->slug, [
            'qty' => $p->qty + 1,
            'size' => sizeof($p->sizes) + 1,
        ])->assertStatus(422)
            ->assertJsonPath('errors.qty', ["The qty may not be greater than {$p->qty}."])
            ->assertJsonPath('errors.size', ["The size may not be greater than {$sizesLen}."])
            ->assertJsonPath('errors.color', ['The color field is required.']);
        $this->assertEquals(0, CartSet::content()->count());
    }

    public function testAnyUserCanAddToWishlist()
    {
        $this->createCart(null, 4);
        [$p, $items] = $this->createCart(null, 5, null, true);

        $this->assertEquals(
            $items->last()->product_id,
            $p->id
        );
        $this->assertCount(5, CartSet::instance('wish')->content());
        $this->assertCount(4, CartSet::instance()->content());

        $user = $this->signIn();
        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id
        ]);
        $this->assertCount(5, CartSet::instance('wish')->content());
        $this->assertCount(4, CartSet::instance()->content());
    }

    public function testUserCartRemainsAfterLogOut()
    {
        // $this->withoutExceptionHandling();
        $user = $this->signIn();

        [$cartProduct, $cartItems] = $this->createCart(null, 4, [9]);
        [$p, $items] = $this->createCart(null, 5, [3], true);

        $this->assertCount(5, CartSet::instance('wish')->content());
        $this->assertCount(4, CartSet::instance()->content());
        $this->assertNotEquals($cartProduct->slug, $p->slug);

        session()->invalidate();
        $this->signIn($user);
        $this->assertCount(5, CartSet::instance('wish')->content());
        $this->assertCount(4, CartSet::instance()->content());
    }

    public function testUpdatingCartRequiresValidData()
    {
        [$p, $item] = $this->createCart();
        $colorsLen = sizeof($p->sizes);

        $this->putJson('/cart/' . $p->slug . '/' . $item->id, [
            'qty' => $p->qty + 5,
            'color' => 500
        ])->assertStatus(422)
            ->assertJsonPath('errors.qty', ["The qty may not be greater than {$p->qty}."])
            ->assertJsonPath('errors.color', ["The color may not be greater than {$colorsLen}."])
            ->assertJsonPath('errors.size', ['The size field is required.']);
    }

    public function testUpdaingCartItemRequiresItemToBeExisted()
    {
        [$p, $item] = $this->createCart();
        $this->putJson('/cart/' . $p->slug . '/' . 8, [
            'qty' => 60,
            'color' => 0,
            'size' => $item->size === 1 ? 0 : 2
        ])->assertNotFound()
            ->assertJson(['not_found']);
    }

    public function testUpdateIngCartRequiresCartToHaveItems()
    {
        $p = factory(Product::class)->create();

        $this->putJson('/cart/' . $p->slug . '/' . 8, [
            'qty' => 60,
            'color' => 0,
            'size' => 2
        ])->assertOk()
            ->assertJson(['empty']);
    }

    public function testAnyOneCanUpdateCart()
    {
        $this->withoutExceptionHandling();
        [$p, $items] = $this->createCart(null, 5, [3, 7, 2, 25, 45]);

        $item = $items->last();

        $this->assertEquals(45, $item->qty);

        $this->putJson('/cart/' . $p->slug . '/' . $item->id, [
            'qty' => 60,
            'color' => 0,
            'size' => $item->size === 1 ? 0 : 2
        ])->assertOk()
            ->assertExactJson(['updated' => true]);

        $item = CartSet::get($item->id);
        $this->assertEquals(60, $item->qty);
        $this->assertEquals(0, $item->color);

        $this->signIn();

        $items = CartSet::content();
        $item = $items->last();

        $this->assertEquals(60, $item->qty);

        // check cart already saved into database
        $this->assertDatabaseHas('cart_items', [
            'id' => (string) $item->id,
            'product_id' => $p->id,
            'qty' => (string) 60
        ]);

        $this->putJson('/cart/' . $p->slug . '/' . $item->id, [
            'qty' => 25,
            'color' => 0,
            'size' => 1
        ])->assertOk()
            ->assertExactJson(['updated' => true]);

        $item = CartSet::get($item->id);
        $this->assertEquals(25, $item->qty);
    }

    public function testDeletingCartItemRequiresCartToHaveItems()
    {
        $p = factory(Product::class)->create();

        $this->postJson('/cart/' . 1 . '/delete')->assertOk()
            ->assertJson(['empty']);
    }

    public function testDeletingCartItemRequiresItemExistence()
    {
        [$p, $item] = $this->createCart();
        $this->postJson('/cart/' . 8 . '/delete')->assertNotFound()
            ->assertJson(['not_found']);
    }

    public function testDeletingCartItems()
    {
        $this->withoutExceptionHandling();
        [$p, $item] = $this->createCart();
        $this->assertCount(1, CartSet::content());
        $this->postJson('/cart/' . $item->id . '/delete')
            ->assertStatus(204);
        $this->assertCount(0, CartSet::content());

        $this->signIn();
        [$p, $item] = $this->createCart();
        $this->assertCount(1, CartSet::content());
        $this->postJson('/cart/' . $item->id . '/delete')
            ->assertStatus(204);
        $this->assertCount(0, CartSet::content());
    }

    public function testCartCanBeDestroyed()
    {
        $this->withoutExceptionHandling();
        [$p, $item] = $this->createCart(null, 5);
        $this->assertCount(5, CartSet::content());
        $this->deleteJson('/cart/all')
            ->assertStatus(204);
        $this->assertCount(0, CartSet::content());

        $this->signIn();
        [$p, $item] = $this->createCart(null, 5);

        $this->actingAs($user = factory(User::class)->create());
        CartSet::afterRegister($user);
        [$p, $item] = $this->createCart(null, 7);

        $this->assertCount(7, CartSet::content());
        $this->deleteJson('/cart/all')
            ->assertStatus(204);
        $this->assertCount(0, CartSet::content());
    }

    public function testWishlistCanBeDestroyed()
    {
        $this->withoutExceptionHandling();
        $this->createCart(null, 7);
        [$p, $item] = $this->createCart(null, 5, null, true);

        $this->assertCount(5, CartSet::instance('wish')->content());
        $this->deleteJson('/cart/delete/wish')
            ->assertStatus(204);
        $this->assertCount(0, CartSet::instance('wish')->content());
        $this->assertCount(7, CartSet::instance()->content());

        $this->signIn();
        [$p, $item] = $this->createCart(null, 5, null, true);

        $this->actingAs($user = factory(User::class)->create());
        CartSet::afterRegister($user);
        [$p, $item] = $this->createCart(null, 7, null, true);
        $this->createCart(null, 15);

        $this->assertCount(7, CartSet::instance('wish')->content());
        $this->deleteJson('/cart/delete/wish')
            ->assertStatus(204);
        $this->assertCount(0, CartSet::instance('wish')->content());
        $this->assertCount(15, CartSet::instance()->content());
    }

    public function testAnyOneCanGetCartList()
    {
        [$p, $items] = $this->createCart(null, 5);

        $this->get('/cart')
            ->assertOk();
    }

    public function testCartSetUpdateProducts()
    {
        $p = factory(Product::class)->create([
            'qty' => 25
        ]);
        [, $items] = $this->createCart($p, 1, [24]);

        $p = Product::find($p->id);

        $this->assertEquals(25, $p->qty);

        $p->qty = 12;
        $p->update();

        $this->assertEquals(12, $p->qty);

        $item = (CartSet::instance()->content())->first();
        $this->assertEquals(25, $item->product->qty);

        $items = CartSet::instance()->updateProducts()->content();
        $this->assertEquals(12, $items->first()->product->qty);
        $this->assertTrue($items->first() instanceof CartItem);
    }

    public function testCartChekoutCheckForProductAmount()
    {
        $this->withoutExceptionHandling();
        $p = factory(Product::class)->create([
            'qty' => 25
        ]);
        [, $items] = $this->createCart($p, 3, [24]);

        $this->assertCount(3, CartSet::instance()->content());
        $p = Product::find($p->id);
        $p->qty = 0;
        $p->update();

        $this->get('/en/cart/checkout')
            ->assertOk()
            ->assertSessionHas('productOut');

        $this->assertCount(2, CartSet::instance()->content());
        $this->assertDatabaseMissing('cart_items', ['id' => $items->first()->id]);
    }

    public function testCartChekoutWithAuth()
    {
        $this->signIn();
        $this->testCartChekoutCheckForProductAmount();
    }

    /**
     * @dataProvider cartCheckoutCheckForItemAmountDataProvider
     *
     * @param [type] $arr
     * @return void
     */
    public function testCartCheckoutCheckForItemAmount($arr)
    {
        [$pQty, $cQty, $pQtynd, $to] = $arr;

        $p = factory(Product::class)->create([
            'qty' => $pQty
        ]);
        [, $items] = $this->createCart($p, 5, [$cQty]);

        $p = Product::find($p->id);
        $p->qty = $pQtynd;
        $p->update();

        $this->get('/en/cart/checkout')
            ->assertOk()
            ->assertSessionHas('productAmount');
    }

    /**
     * @dataProvider cartCheckoutCheckForItemAmountDataProvider
     *
     * @param [type] $arr
     * @return void
     */
    public function testCartCheckoutCheckAmountWithAuth($arr)
    {
        $this->signIn();
        $this->testCartCheckoutCheckForItemAmount($arr);
    }

    /**
     *
     * @return void
     */
    public function cartCheckoutCheckForItemAmountDataProvider()
    {
        return [
            ['15, 5' => [25, 15, 10, 5]],
            ['40, 20' => [55, 40, 20, 20]],
            ['9, 1' => [15, 9, 1, 1]],
            ['20, 1' => [30, 20, 9, 1]]
        ];
    }

    public function testItHaveOrderContent()
    {
        [$p, $items] = $this->createCart(null, 5, [3]);

        $res = CartSet::orderContent();

        $arr = json_decode($res->items);

        $this->assertIsArray($arr);
        $this->assertNotNull($arr[0]->name);
        $this->assertNotNull($arr[0]->quantity);
        $this->assertEquals(3, $arr[0]->quantity);
    }

    public function testUserCanGetOrderContent()
    {
        $this->signIn();
        [$p, $items] = $this->createCart(null, 5);

        $res = CartSet::orderContent();

        $arr = json_decode($res->items);

        $this->assertIsArray($arr);
        $this->assertNotNull($arr[0]->name);
        $this->assertNotNull($arr[0]->quantity);
    }
}
