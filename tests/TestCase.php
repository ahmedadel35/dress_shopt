<?php

namespace Tests;

use App\Product;
use App\User;
use CartSet;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Arr;
use Mcamara\LaravelLocalization\LaravelLocalization;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public static function setUpBeforeClass(): void
    {
        putenv(LaravelLocalization::ENV_ROUTE_KEY . '=en');
        parent::setUpBeforeClass();
    }

    public static function tearDownAfterClass(): void
    {
        putenv(LaravelLocalization::ENV_ROUTE_KEY);
        parent::tearDownAfterClass();
    }

    public function signIn(?User $user = null, array $attr = []): User
    {
        
        $user = $user ?? factory(User::class)->create($attr);

        $this->actingAs($user);
        
        CartSet::afterRegister($user);
        $user = User::find($user->id);
        CartSet::afterLoggedIn($user);

        return $user;
    }

    public function createCart(
        ?Product $product = null,
        int $count = 1,
        ?array $qtyArr = null,
        bool $wish = false
    ): array {

        foreach (range(1, $count) as $i) {
            $product = isset($product) && $i === 1
                ? $product
                : $this->getProduct();
            $qty = $qtyArr[$i - 1] ?? random_int(1, $product->qty);
            $data = [
                'qty' => $qty,
                'size' => array_search(
                    Arr::random($product->sizes),
                    $product->sizes
                ),
                'color' => array_search(
                    Arr::random($product->colors),
                    $product->colors
                )
            ];

            $res = $this->postJson('/cart/' . $product->slug, array_merge($data, $wish ? ['wish' => true] : []))->assertOk()
                ->assertJsonPath('item.product_id', $product->id);
        }

        $items = CartSet::content();
        if ($items->count() === 1) {
            $items = $items->first();
        }

        return [$product, $items];
    }

    public function getProduct(?Product $product = null)
    {
        return $product ?? factory(Product::class)->create();
    }
}
