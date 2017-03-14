<?php

namespace Laralum\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public $table = 'laralum_shop_items';
    public $fillable = [
        'name', 'description', 'price', 'units', 'category_id'
    ];

    /**
     * Return the item category.
     */
     public function category()
     {
         return $this->belongsTo(Category::class);
     }

     /**
      * Return the item orders.
      */
     public function orders()
     {
         return $this->belongsToMany(Order::class, 'laralum_shop_item_order');
     }

     /**
      * Return true if the item is available.
      */
     public function available()
     {
         return $this->units !== 0;
     }
}
