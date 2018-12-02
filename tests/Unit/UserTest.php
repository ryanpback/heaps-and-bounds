<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserService;
use App\Traits\FactoryTraits;

use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UserTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;
    use FactoryTraits;

    /**
     * Test new user validation - without email
     * @expectedException Illuminate\Database\QueryException
     * @return void
     */
    public function testCannotCreateUserWithoutEmail()
    {
        $user = User::create([
            'password' => Hash::make('password')
        ]);

        $this->setExpectedException('Illuminate\Database\QueryException');
    }

    /**
     * Test new user validation - without password
     * @expectedException Illuminate\Database\QueryException
     * @return void
     */
    public function testCannotCreateUserWithoutPassword()
    {
        $user = User::create([
            'email' => 'ryanpback@gmail.com'
        ]);

        $this->setExpectedException('Illuminate\Database\QueryException');
    }

    /**
    * Update user
    *
    * @return void
    */
    public function testUpdateUser()
    {
        $user = factory(User::class, 'new')->create();

        $newUserData = [
            'first_name'    => 'Bob',
            'last_name'     => 'Smith'
        ];

        $service = new UserService($user);
        $service->update($newUserData);

        $user = $service->update($newUserData);

        $this->assertEquals('Bob', $user->first_name);
        $this->assertEquals('Smith', $user->last_name);
    }

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
        $this->assertEquals(9, count($user->getMyTrashedPosts()));
        $this->assertEquals(0, count($user->posts()->get()));
    }

    /**
    * When user is restored, their posts are restored as well
    *
    * @return void
    */
    public function testWhenUserIsRestoredTheirPostsAreRestoredAsWell()
    {
        $users      = $this->createUsersWithPosts(1, 9);
        $user       = $users[0];

        $service    = new UserService($user);
        $service->deactivate();

        $this->assertEquals(9, count($user->getMyTrashedPosts()));
        $this->assertFalse($user->active);

        $service->restore();

        $this->assertTrue($user->active);
        $this->assertEquals(0, count($user->getMyTrashedPosts()));
        $this->assertEquals(9, count($user->posts()->get()));
    }
}
