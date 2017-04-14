<?php

namespace Laralum\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Laralum\Shop\Models\User;

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
     * @param mixed $user
     * @param mixed $order
     *
     * @return bool
     */
    public function access($user)
    {
        return User::findOrFail($user->id)->hasPermission('laralum::shop.order.access');
    }

    /**
     * Determine if the current user can change the order status.
     *
     * @param mixed $user
     * @param mixed $order
     *
     * @return bool
     */
    public function status($user, $order)
    {
        return User::findOrFail($user->id)->hasPermission('laralum::shop.order.status');
    }

    /**
     * Determine if the current user can view the order.
     *
     * @param mixed $user
     * @param mixed $order
     *
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
