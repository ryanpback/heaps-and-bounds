<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * After user is deleted, delete all relationships
     * that the user has.
     *
     * @param User $u
     * @return void
     */
    public function deleted(User $u)
    {
        $u->posts()->delete();
    }
}
