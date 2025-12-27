<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'user']);
    }
    public function trashProducts(User $user): bool
    {
        return in_array($user->role, ['admin']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Product $product): bool
    {
        return in_array($user->role, ['admin', 'user']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'user']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Product $product): bool
    {
        return in_array($user->role, ['admin']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Product $product): bool
    {
        return in_array($user->role, ['admin']);
    }

    public function restore(User $user, Product $product)
    {
        return in_array($user->role, ['admin']);
    }

    public function forceDelete(User $user, Product $product)
    {
        return in_array($user->role, ['admin']);
    }

    public function updateStatus(User $user, Product $product)
    {
        return in_array($user->role, ['admin']);
    }
}
