<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Post;
use App\Services\UserQuestionService;
use App\Traits\FactoryTraits;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UserQuestionTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;
    use FactoryTraits;

    /**
     * Can't create question without title
     *
     * @expectedException Illuminate\Database\QueryException
     * @return void
     */
    public function testCantCreateQuestionWithoutTitle()
    {
        $user = factory(User::class, 'new')->create();

        $questionData = [
            'question_content' => 'I have content but no title',
        ];

        $service = new UserQuestionService($user);
        $service->createQuestion($questionData);

        $this->setExpectedException('Illuminate\Database\QueryException');
    }

    /**
     * Can't create question without title
     *
     * @expectedException Illuminate\Database\QueryException
     * @return void
     */
    public function testCantCreateQuestionWithoutQuestionContent()
    {
        $user = factory(User::class, 'new')->create();

        $questionData = [
            'title' => 'I have a title but no question content',
        ];

        $service = new UserQuestionService($user);
        $service->createQuestion($questionData);

        $this->setExpectedException('Illuminate\Database\QueryException');
    }

    /**
     * User can successfully create a question
     *
     * @return void
     */
    public function testUserCanSuccessfullyCreateAQuestion()
    {
        $user = factory(User::class, 'new')->create();

        $questionData = [
            'question_content'  => 'I have question content',
            'title'             => 'I have a title',
        ];

        $service = new UserQuestionService($user);
        $service->createQuestion($questionData);

        $this->assertEquals(1, $user->questions()->count());
    }

    /**
     * User can successfully update a Question
     *
     * @return void
     */
    public function testUserCanSuccessfullyUpdateAQuestion()
    {
        // createUsersWithQuestions is a trait method
        $users      = $this->createUsersWithQuestions(1, 1);
        $user       = $users[0];
        $question   = $user->questions()->first();

        // save for assertion
        $originalQuestionTitle      = $question->title;
        $originalQuestionContent    = $question->question_content;

        $data = [
            'question_id'       => $question->id,
            'question_content'  => 'New content',
            'title'             => 'New title',
        ];

        $service            = new UserQuestionService($user);
        $updatedQuestion    = $service->updateQuestion($data);

        $this->assertNotEquals($originalQuestionTitle, $updatedQuestion->title);
        $this->assertNotEquals($originalQuestionContent, $updatedQuestion->question_content);
        $this->assertEquals('New title', $updatedQuestion->title);
        $this->assertEquals('New content', $updatedQuestion->question_content);
    }

    /**
     * User can successfully delete (force delete) a question
     *
     * @return void
     */
    public function testUserCanDeleteAQuestion()
    {
        // createUsersWithQuestions is a trait method
        $users = $this->createUsersWithQuestions(1, 2);
        $user = $users[0];
        $question = $user->questions()->first();

        $this->assertEquals(2, count($user->questions()->get()));

        $service = new UserQuestionService($user);
        $service->deleteQuestion($question->id);

        // save for when/how I decide to implement soft deletes on questions
        // $deletedQuestions = $user->getMyTrashedQuestions();
        // $this->assertEquals(0, count($deletedQuestions));

        $this->assertEquals(1, count($user->questions()->get()));
    }
}
