<?php

namespace App;

use Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rate extends Model
{
    protected $guarded = [];

    protected $perPage = 7;

    protected $appends = [
        'updated',
        'can_update'
    ];

    protected $casts = [
        'rate' => 'float'
    ];

    public function getUpdatedAttribute(): string
    {
        return $this->updated_at ? $this->updated_at->locale(
            \Session::get('locale') ?? 'en'
        )->translatedFormat('d M Y') : '';
    }

    public function getCanUpdateAttribute(): bool
    {
        return Gate::allows('update-model', $this);
    }

    /**
     * link to owner user
     *
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * link to product
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
