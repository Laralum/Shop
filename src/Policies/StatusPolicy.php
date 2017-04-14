<?php

namespace Laralum\Shop\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Laralum\Shop\Models\Settings;
use Laralum\Shop\Models\Status;
use Laralum\Shop\Models\User;

class StatusPolicy
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
        if ($ability != 'delete' && User::findOrFail($user->id)->superAdmin()) {
            return true;
        }
    }

    /**
     * Determine if the current user can access the shop categories.
     *
     * @param mixed $user
     *
     * @return bool
     */
    public function access($user)
    {
        return User::findOrFail($user->id)->hasPermission('laralum::shop.status.access');
    }

    /**
     * Determine if the current user can create a shop category.
     *
     * @param mixed $user
     *
     * @return bool
     */
    public function create($user)
    {
        return User::findOrFail($user->id)->hasPermission('laralum::shop.status.create');
    }

    /**
     * Determine if the current user can update a shop status.
     *
     * @param mixed $user
     * @param mixed $status
     *
     * @return bool
     */
    public function update($user, $status)
    {
        return User::findOrFail($user->id)->hasPermission('laralum::shop.status.update');
    }

    /**
     * Determine if the current user can delete a shop status.
     *
     * @param mixed $user
     * @param mixed $status
     *
     * @return bool
     */
    public function delete($user, $status)
    {
        $settings = Settings::first();

        if ($status->id == $settings->default_status || $status->id == $settings->paid_status) {
            return false;
        }

        $user = User::findOrFail($user->id);

        return $user->superAdmin() || $user->hasPermission('laralum::shop.status.delete');
    }
}
