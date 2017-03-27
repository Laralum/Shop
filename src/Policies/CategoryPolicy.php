<?php

namespace Laralum\Shop\Policies;

use Laralum\Shop\Models\User;
use Laralum\Shop\Models\Category;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
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
     * @param  mixed  $user
     * @param  mixed  $category
     * @return bool
     */
    public function access($user)
    {
        return User::findOrFail($user->id)->hasPermission('laralum::shop.category.access');
    }

    /**
     * Determine if the current user can create a shop category.
     *
     * @param  mixed  $user
     * @param  mixed  $category
     * @return bool
     */
    public function create($user)
    {
        return User::findOrFail($user->id)->hasPermission('laralum::shop.category.create');
    }

    /**
     * Determine if the current user can update a shop category.
     *
     * @param  mixed  $user
     * @param  mixed  $category
     * @return bool
     */
    public function update($user, $category)
    {
        return User::findOrFail($user->id)->hasPermission('laralum::shop.category.update');
    }

    /**
     * Determine if the current user can delete a shop category.
     *
     * @param  mixed  $user
     * @param  mixed  $category
     * @return bool
     */
    public function delete($user, $category)
    {
        if (Category::first()->id == $category->id) {
            return false;
        }

        $user = User::findOrFail($user->id);

        return ($user->superAdmin() || $user->hasPermission('laralum::shop.category.delete'));
    }

}
