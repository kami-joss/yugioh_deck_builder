<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(User $user)
    {
        return $user->id === auth('sanctum')->user()->id;
    }

    public function delete(User $user)
    {
        return $user->id === auth('sanctum')->user()->id;
    }

    public function show(User $user)
    {
        return $user->id === auth('sanctum')->user()->id;
    }

    public function showFavorites(User $user)
    {
        return $user->id === auth('sanctum')->user()->id;
    }

    public function showDecks(User $user)
    {
        return $user->id === auth('sanctum')->user()->id;
    }
}
