<?php

namespace Laralum\Shop\Policies;

use Laralum\Shop\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Filters the authoritzation.
     *
     * @param mixed $user
     * @param mixed $ability
     */
    public function before($user, $ability)
    {
        if (User::findOrFail($user->id)->superAdmin()) {
            return true;
        }
    }

    /**
     * Determine if the current user can access the payments module.
     *
     * @param  mixed  $user
     * @return bool
     */
    public function access($user, $order)
    {
        return User::findOrFail($user->id)->hasPermission('laralum::shop.order');
    }

}
