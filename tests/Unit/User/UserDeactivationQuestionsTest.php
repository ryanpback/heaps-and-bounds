<?php

namespace Tests\Unit\User;

use App\Models\User;
use App\Models\Question;
use App\Services\UserService;
use App\Traits\FactoryTraits;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UserDeactivationQuestionsTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;
    use FactoryTraits;

    /**
     * When user is deactivated, their posts are soft deleted
     *
     * @return void
     */
    public function testWhenUserIsDeactivatedTheirPostsAreSoftDeleted()
    {
        $users  = $this->createUsersWithQuestions(1, 9);
        $user   = $users[0];

        $this->assertEquals(9, count($user->questions()->get()));

        $service = new UserService($user);
        $service->deactivate();

        $deactivatedUsersQuestions = Question::getAllUsersQuestions($user->id, true)->get();

        $this->assertEquals(9, $deactivatedUsersQuestions->count());
        $this->assertEquals(0, $user->questions()->get()->count());
    }

    /**
     * When user is restored, their posts are restored as well
     *
     * @return void
     */
    public function testWhenUserIsReactivatedTheirPostsAreRestoredAsWell()
    {
        $users      = $this->createUsersWithQuestions(1, 9);
        $user       = $users[0];

        $service    = new UserService($user);
        $service->deactivate();

        $this->assertEquals(9, Question::getAllUsersQuestions($user->id, true)->count());
        $this->assertEquals(0, Question::getAllUsersQuestions($user->id, false)->count());

        $service->reactivate();

        $this->assertEquals(9, $user->questions()->count());
    }
}
