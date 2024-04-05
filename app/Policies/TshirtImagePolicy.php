<?php

namespace App\Policies;

use App\Models\TshirtImage;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TshirtImagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->user_type == 'A';
    }

    public function viewMinhas(User $user): bool
    {
        return $user->user_type == 'A' || $user->user_type == 'C';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TshirtImage $tshirtImage): bool
    {
        return $user->user_type == 'A';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->user_type == 'A';
    }

    public function store(User $user): bool
    {
        return $user->user_type == 'A'|| $user->user_type == 'C';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TshirtImage $tshirtImage): bool
    {
        return $user->user_type == 'A';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TshirtImage $tshirtImage): bool
    {
        return $user->user_type == 'A' || $user->id === $tshirtImage->customer_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TshirtImage $tshirtImage): bool
    {
        return $user->user_type == 'A';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TshirtImage $tshirtImage): bool
    {
        return $user->user_type == 'A';
    }
}
