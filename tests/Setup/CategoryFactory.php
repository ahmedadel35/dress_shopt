<?php

declare(strict_types=1);

namespace Tests\Setup;

use App\Category;
use App\Product;

class CategoryFactory
{
    private $sub_count = null;
    private $subId;
    private $product_count = null;
    private $productId;


    public function wSub(int $count = 1, int $subId = 2): self
    {
        $this->sub_count = $count;
        $this->subId = $subId;
        return $this;
    }

    public function wPro(int $count = 1, int $productId = 1): self
    {
        $this->product_count = $count;
        $this->productId = $productId;
        return $this;
    }

    /**
     * Undocumented function
     *
     * @return array
     *      @type \App\Category $c
     */
    public function create(): array
    {
        return $this->set();
    }

    public function make(
        bool $is_sub = false
    ): array {
        return $is_sub ? $this->set(null, 'make') : $this->set(null, null, 'make');
    }

    public function raw(
        bool $is_sub = false
    ): array {
        return $is_sub ? $this->set(null, 'raw') : $this->set(null, null, 'raw');
    }

    /**
     * Undocumented function
     *
     * @param string|null $cat_method
     * @param string|null $sub_method
     * @param string $product_method
     * @return array[\App\Category,\App\Category[]]
     */
    public function set(
        ?string $cat_method = 'create',
        ?string $sub_method = 'create',
        string $product_method = 'create'
    ): array {
        /** @var \App\Category $c */
        $c = factory(Category::class)->create();

        if (!$this->sub_count) {
            return [$c];
        }

        /** @var \App\Category[] $subcats */
        $subcats = $c->sub_cats()->saveMany(
            factory(Category::class, $this->sub_count)->make()
        );

        $sub = $subcats->where('id', $this->subId)[0];

        if (!$this->product_count) {
            return [$c, $sub, $subcats];
        }

        $products = $sub->products()->saveMany(
            factory(Product::class, $this->product_count)->make()
        );

        $p = $products->where('id', $this->productId)[0];

        return [$c, $sub, $p, $subcats, $products];
    }
}
