<?php

namespace Tests\Unit\User;

use App\Models\User;
use App\Services\UserService;
use App\Traits\FactoryTraits;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UserDeactivationTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;
    use FactoryTraits;

    /**
    * Deactivate User
    *
    * @return void
    */
    public function testDeactivateUser()
    {
        $user   = factory(User::class, 'new')->create();
        $userId = $user->id;

        $service = new UserService($user);
        $service->deactivate();

        $user = User::find($user->id);
        $this->assertNull($user);

        $user = User::withTrashed()->find($userId);
        $this->assertEquals($user->id, $userId);
    }

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

        $this->assertFalse($user->active);
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
        $this->assertFalse($user->active);

        $service->reactivate();

        $this->assertTrue($user->active);
        $this->assertEquals(0, $user->getMyTrashedPosts()->count());
        $this->assertEquals(9, $user->posts()->get()->count());
    }
}
