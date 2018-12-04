<?php

namespace App\Services;

use App\Models\Question;

class UserQuestionService
{
    private $user;

    /**
     * Construct a new instance
     *
     * @param User $u
     */
    public function __construct(User $u)
    {
        $this->user = $u;
    }
}
