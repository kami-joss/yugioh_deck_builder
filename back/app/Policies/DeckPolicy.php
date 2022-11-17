<?php

namespace App\Policies;

use App\Models\Deck;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeckPolicy
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

    public function show(User $user, Deck $deck) {
        return $deck->public || $user->id === $deck->user_id;
    }

    public function update(User $user, Deck $deck)
    {
        return $user->id === $deck->user_id;
    }

    public function delete(User $user, Deck $deck)
    {
        return $user->id === $deck->user_id;
    }
}
