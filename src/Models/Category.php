<?php

namespace Laralum\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $table = 'laralum_shop_categories';
    public $fillable = [
        'name',
    ];

     /**
      * Return all the category items.
      */
     public function items()
     {
         return $this->hasMany(Item::class);
     }
}
