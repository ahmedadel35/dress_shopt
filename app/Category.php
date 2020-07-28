<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use Sluggable;

    protected $guarded = [];

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

    public function sub_cats(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    /**
     * get category products
     *
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->HasMany(Product::class, 'category_slug', 'slug');
    }
}
