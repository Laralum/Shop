<?php

namespace Laralum\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public $table = 'laralum_shop_status';
    public $fillable = [
        'name', 'color',
    ];

    /**
     * Returns the orders associated with that status.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
