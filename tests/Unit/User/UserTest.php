<?php

namespace Tests\Unit\User;

use App\Models\User;
use App\Services\User\UserService;
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
     *
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
     *
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
}
