<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;

class Order extends Model
{
    protected $guarded = [];

    protected $hidden = ['id', 'orderNum'];

    protected $appends = ['enc_id'];

    protected $casts = [
        'total' => 'float',
        'qty' => 'int',
        'paymentStatus' => 'bool'
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    // public function getRouteKeyName()
    // {
    //     return 'orderNum';
    // }

    public function getEncIdAttribute()
    {
        return HashIds::connection('alternative')->encode($this->id);
    }

    public static function decId($id)
    {
        return Hashids::connection('alternative')->decode($id)[0];
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public function itp(): HasMany
    {
        return $this->items()->latest()->with('product');
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function createNew(array $attrs): self
    {
        return Order::create([
            'orderNum' => self::createUniqueOrderNum()
        ] + $attrs);
    }

    public static function createUniqueOrderNum()
    {
        $orderNum = bin2hex(random_bytes(7));
        $found = Order::select(DB::raw('COUNT(id) as cid'))
            ->where('orderNum', $orderNum)
            ->get('cid')->first();

        if ($found->cid > 0) {
            $orderNum = self::createUniqueOrderNum();
        }

        return $orderNum;
    }

    /**
     * Undocumented function
     *
     * @param \App\CartItem[] $items
     * @return void
     */
    public function addItems(array $items, bool $keyChanged = false)
    {
        foreach ($items as &$item) {
            if ($keyChanged) {
                // $item['title'] = $item['name'];
                $item['qty'] = $item['quantity'];
                $item['price'] = ((int) $item['amount_cents']) /100;
                $ex = explode('#oRd', $item['description']);
                if (isset($ex[1])) {
                    [$pId, $size, $color] = explode('__', $ex[1]);
                } else {
                    [$pId, $size, $color] = [1, 0, 0];
                }
                $item['product_id'] = $pId;
                $item['size'] = $size;
                $item['color'] = $color;
                $item['sub_total'] =  round($item['qty'] * $item['price'], 2);
                unset($item['description']);
                unset($item['name']);
                unset($item['quantity']);
                unset($item['amount_cents']);
            }
            $product = Product::find($item['product_id']);
            $product->decrement('qty', $item['qty']);
            $product->update();
            unset($item['id']);
            unset($item['product']);
            unset($item['cart_id']);
        }

        // add items to order items
        $this->items()->createMany($items);
    }
}
