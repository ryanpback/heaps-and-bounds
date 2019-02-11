<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Question;
use App\Models\Cheer;

class CheerService
{
    private $cheerable;

    /**
     * Construct a new instance
     *
     * @param int       $cheerableId
     * @param string    $cheerableType
     */
    public function __construct(int $cheerableId, string $cheerableType)
    {
        if ($cheerableType === 'post') {
            $this->cheerable = Post::findOrFail($cheerableId);
        }

        if ($cheerableType === 'question') {
            $this->cheerable = Question::findOrFail($cheerableId);
        }
    }

    /**
     * User can add a cheer to a cheerable. If the cheerable has been
     * cheered by the user, the cheerable will be uncheered.
     * One cheer per cheerable by user.
     *
     * @param integer $userId
     * @return void
     */
    public function cheer(int $userId)
    {
        $cheered = Cheer::isCheeredByUser(
            $this->cheerable->id,
            get_class($this->cheerable),
            $userId
        )
        ->first();

        if (!is_null($cheered)) {
            return $cheered->delete();
        }

        $cheer          = new Cheer();
        $cheer->user_id = $userId;

        return $this->cheerable->cheers()->save($cheer);
    }
}
