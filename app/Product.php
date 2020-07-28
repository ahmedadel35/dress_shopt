<?php

namespace App;

use CartSet;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use Sluggable, SoftDeletes;

    public const PER_PAGE = 15;

    protected $guarded = [];

    protected $casts = [
        'price' => 'float',
        'save' => 'float',
        'colors' => 'array',
        'sizes' => 'array',
        'images' => 'array'
    ];

    protected $appends = [
        'saved_price',
        'image',
        'rate_avg'
    ];

    // protected $dates = [
    //     'created_at',
    //     'updated_at',
    //     'deleted_at'
    // ];

    /**
     * configure slugable
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * load public storage link
     *
     * @return string
     */
    public function getImageAttribute()
    {
        return url($this->images[0]);
    }

    /**
     * calculate saved price
     *
     * @return float
     */
    public function getSavedPriceAttribute(): float
    {
        return round($this->price - ($this->save / 100 * $this->price), 2);
    }

    public function getCartResAttribute(): array
    {
        return [
            'user_id' => $this->user_id,
            'price' => $this->price,
            'save' => $this->save,
            'image' => $this->images[0],
            'qty' => $this->qty
        ];
    }

    public function getRateAvgAttribute(): float
    {
        return round($this->rates->avg('rate'), 1);
    }

    public function getMiniInfoAttribute(): string
    {
        return substr($this->info, 0, 150);
    }

    /**
     * product owner
     *
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * get product parent category
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->BelongsTo(Category::class, 'category_slug', 'slug');
    }

    /**
     * link product tags
     *
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tag', 'product_id', 'tag_slug');
    }

    /**
     * link product more information
     *
     * @return HasOne
     */
    public function pi(): HasOne
    {
        return $this->hasOne(ProductInfo::class);
    }

    /**
     * link product to daily deals list
     *
     * @return HasMany
     */
    public function daily(): HasMany
    {
        return $this->hasMany(DailyDeal::class, 'product_slug', 'slug');
    }

    /**
     * link product to owned rates
     *
     * @return HasMany
     */
    public function rates(): HasMany
    {
        return $this->hasMany(Rate::class)->with('owner');
    }

    /**
     * link product to cart
     *
     * @return BelongsTo
     */
    public function cart(): BelongsToMany
    {
        return $this->belongsToMany(Cart::class, 'product_cart');
    }

    /**
     * add product to cart
     *
     * @param integer $qty
     * @param integer|null $sizeInx
     * @param integer|null $colorInx
     * @param boolean $wish
     * @return void
     */
    public function addToCart(
        int $qty = 1,
        ?int $sizeInx = null,
        ?int $colorInx = null,
        bool $wish = false
    ) {
        return CartSet::instance($wish ? 'wish' : null)
            ->add(
                $this->id,
                $this->saved_price,
                $qty,
                $sizeInx ?? 0,
                $colorInx ?? 0
            );
    }
}
