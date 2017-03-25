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
     * Determine if the current user can view the order.
     *
     * @param  mixed  $user
     * @param  mixed  $order
     * @return bool
     */
    public function publicView($user, $order)
    {
        if ($order->user->id != $user->id) {
            return false;
        }

        return true;
    }

}
