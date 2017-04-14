<?php

namespace Laralum\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Laralum\Shop\Models\User;

class ItemPolicy
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
     * Determine if the current user can access the shop items.
     *
     * @param mixed $user
     * @param mixed $item
     *
     * @return bool
     */
    public function access($user)
    {
        return User::findOrFail($user->id)->hasPermission('laralum::shop.item.access');
    }

    /**
     * Determine if the current user can create a shop item.
     *
     * @param mixed $user
     * @param mixed $item
     *
     * @return bool
     */
    public function create($user)
    {
        return User::findOrFail($user->id)->hasPermission('laralum::shop.item.create');
    }

    /**
     * Determine if the current user can update a shop item.
     *
     * @param mixed $user
     * @param mixed $item
     *
     * @return bool
     */
    public function update($user, $item)
    {
        return User::findOrFail($user->id)->hasPermission('laralum::shop.item.update');
    }

    /**
     * Determine if the current user can delete a shop item.
     *
     * @param mixed $user
     * @param mixed $item
     *
     * @return bool
     */
    public function delete($user, $item)
    {
        return User::findOrFail($user->id)->hasPermission('laralum::shop.item.delete');
    }
}
