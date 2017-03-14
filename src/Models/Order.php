<?php

namespace Laralum\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $table = 'laralum_shop_orders';
    public $fillable = [
        'status_id', 'user_id'
    ];

    /**
     * Return the item category.
     */
    public function items()
    {
        return $this->belongsToMany(Item::class, 'laralum_shop_item_order');
    }

    /**
     * Return the item category.
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}
