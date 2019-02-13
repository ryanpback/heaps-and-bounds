<?php

namespace Tests\Unit\User;

use App\Models\User;
use App\Services\UserService;
use App\Traits\FactoryTraits;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UserDeactivationPostsTest extends TestCase
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
        $users  = $this->createUsersWithPosts(1, 9);
        $user   = $users[0];

        $this->assertEquals(9, count($user->posts()->get()));

        $service = new UserService($user);
        $service->deactivate();

        $this->assertEquals(9, $user->getMyTrashedPosts()->count());
        $this->assertEquals(0, $user->posts()->get()->count());
    }

    /**
     * When user is restored, their posts are restored as well
     *
     * @return void
     */
    public function testWhenUserIsReactivatedTheirPostsAreRestoredAsWell()
    {
        $users      = $this->createUsersWithPosts(1, 9);
        $user       = $users[0];

        $service    = new UserService($user);
        $service->deactivate();

        $this->assertEquals(9, $user->getMyTrashedPosts()->count());

        $service->reactivate();

        $this->assertEquals(0, $user->getMyTrashedPosts()->count());
        $this->assertEquals(9, $user->posts()->get()->count());
    }
}
