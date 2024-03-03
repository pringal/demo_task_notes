<?php

namespace App\Policies;

use App\Models\User;

class NotePolicy
{
    /**
     * Create a new policy instance.
     */
    public function create(User $user){
        return $user->id === auth()->user()->id;
    }
}
