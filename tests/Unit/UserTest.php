<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UserTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

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

        $user->update($newUserData);
        $user->fresh();

        $this->assertEquals('Bob', $user->first_name);
        $this->assertEquals('Smith', $user->last_name);
    }

    /**
    * Delete user
    *
    * @return void
    */
    public function testDeleteUser()
    {
        $user = factory(User::class, 'new')->create();
        $userId = $user->id;
        $user->delete();

        $user = User::find($userId);

        $this->assertNull($user);
    }
}
