<?php

namespace App\Services\Content;

use App\Models\Question;
use App\Models\User;

class QuestionService
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

    /**
     * User question creation
     *
     * @param array $questionData
     * @return Question
     */
    public function createQuestion(array $questionData)
    {
        return $this->user->questions()->create($questionData);
    }

    /**
     * User question update
     *
     * @param array $data
     * @return Question/null
     */
    public function updateQuestion(array $data)
    {
        $question = $this->user->getQuestion($data['question_id']);

        if (!is_null($question)) {
            $question->update($data);
        }

        return $question;
    }

    /**
     * User question deletion (force delete)
     *
     * @param integer $questionId
     * @return Collection/null
     */
    public function deleteQuestion(int $questionId)
    {
        $question = $this->user->getQuestion($questionId);

        if (is_null($question)) {
            return $question;
        }

        $question->forceDelete();

        return $this->user->withQuestions()->get();
    }
}
