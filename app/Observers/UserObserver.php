<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * After user is deleted (soft delete), soft delete
     * all relationships that the user has.
     *
     * @param User $u
     * @return void
     */
    public function deleted(User $u)
    {
        $u->posts()->delete();
    }

    /**
     * After user is restored, restore all relationships
     * that the user has.
     *
     * @param User $u
     * @return void
     */
    public function restored(User $u)
    {
        $u->posts()->restore();
    }
}
