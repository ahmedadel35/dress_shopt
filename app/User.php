<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Vinkla\Hashids\Facades\Hashids;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    public const AdminRole = 4; // do anything
    public const SuperRole = 3; // create - edit - delete
    public const DeliveryRole = 2; // deliver product to user and get paid
    public const NormalUser = 1; // just buy stuff

    protected $appends = [
        'image',
        'enc_id'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean'
    ];

    public function getImageAttribute(): string
    {
        return $this->img;
    }

    public function getRoleTxtAttribute(): string
    {
        if ($this->role === self::AdminRole) {
            return __('admin.roles.admin');
        } elseif ($this->role === self::SuperRole) {
            return __('admin.roles.super');
        } elseif ($this->role === self::DeliveryRole) {
            return __('admin.roles.delivery');
        }

        return '';
    }

    public function getEncIdAttribute()
    {
        return HashIds::encode($this->id);
    }

    public function decId(string $id)
    {
        return Hashids::decode($id);
    }

    public function isAdmin(): bool
    {
        return (int) $this->role === self::AdminRole;
    }

    public function isSuper(): bool
    {
        return (int) $this->role === self::SuperRole;
    }

    public function isDelivery(): bool
    {
        return (int) $this->role === self::DeliveryRole;
    }

    /**
     * link user to owned products
     *
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * link user to owned rates
     *
     * @return HasMany
     */
    public function rates(): HasMany
    {
        return $this->hasMany(Rate::class);
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class)->whereNull('instance')->limit(1);
    }

    public function wishlist(): HasOne
    {
        return $this->hasOne(Cart::class)->whereInstance('wish')->limit(1);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
