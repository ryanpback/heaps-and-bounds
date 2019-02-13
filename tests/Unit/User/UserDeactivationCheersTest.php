<?php

namespace Tests\Unit\User;

use App\Models\User;
use App\Models\Question;
use App\Services\UserService;
use App\Traits\FactoryTraits;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UserDeactivationCheerssTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;
    use FactoryTraits;

    /**
     * When user is deactivated, their cheers are soft deleted
     *
     * @return void
     */
    public function testWhenUserIsDeactivatedTheirCheersAreSoftDeleted()
    {
        $users      = $this->createUsersWithPostsAndQuestionsWithCheers(1, 2, 2);
        $user       = $users[0];

        $service    = new UserService($user);
        $service->deactivate();

        $this->assertEquals(4, $user->cheers()->withTrashed()->count());
    }

    /**
     * When user is restored, their cheers get restored
     *
     * @return void
     */
    public function testWhenUserIsReactivatedTheirCheersAreRestoredAsWell()
    {
        $users = $this->createUsersWithPostsAndQuestionsWithCheers(1, 2, 2);
        $user = $users[0];

        $service = new UserService($user);
        $service->deactivate();
        $service->reactivate();

        $this->assertEquals(4, $user->cheers()->count());
    }
}
