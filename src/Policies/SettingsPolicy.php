<?php

namespace Laralum\Shop\Policies;

use Laralum\Shop\Models\User;
use Laralum\Shop\Models\Status;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingsPolicy
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
     * Determine if the current user can update the shop settings.
     *
     * @param  mixed  $user
     * @return bool
     */
    public function update($user)
    {
        return User::findOrFail($user->id)->hasPermission('laralum::shop.settings');
    }

}
