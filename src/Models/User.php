<?php

namespace Laralum\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Laralum\Payments\Models\User as ExtendedUser;

class User extends ExtendedUser
{
    /**
     * Returns the orders associated with that user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
