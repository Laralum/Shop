<?php

namespace Laralum\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $table = 'laralum_shop_orders';
    public $fillable = [
        'status_id', 'user_id', 'shipping_name', 'tax_percentage_on_buy',
        'shipping_adress', 'shipping_zip', 'shipping_state', 'shipping_city', 'shipping_country',
    ];

    /**
     * Return the item category.
     */
    public function items()
    {
        return $this->belongsToMany(Item::class, 'laralum_shop_item_order')->withPivot('units', 'item_on_buy');
    }

    /**
     * Return the item category.
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * Return the item category.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Return the total order price.
     */
    public function price()
    {
        return number_format(collect($this->items)->map(function ($item) {
            return bcmul($item->pivot->units, unserialize($item->pivot->item_on_buy)['price'], 2);
        })->sum(), 2);
    }

    /**
     * Return the total units in the order.
     */
    public function units()
    {
        return collect($this->items)->map(function ($item) {
            return $item->pivot->units;
        })->sum();
    }

    public function tax()
    {
        return bcdiv(bcmul($this->tax_percentage_on_buy, $this->price(), 2), 100, 2);
    }

    public function totalPrice()
    {
        return bcadd($this->tax(), $this->price(), 2);
    }
}
