<?php

namespace Laralum\Shop\Models;

use Laralum\Users\Models\User as ExtendedUser;

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
