<?php

namespace Tests\Unit\User;

use App\Models\User;
use App\Services\UserService;
use App\Traits\FactoryTraits;

use Tests\TestCase;
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
    * Restore User
    *
    * @return void
    */
    public function testRestoreUser()
    {
        $user   = factory(User::class, 'new')->create();
        $userId = $user->id;

        $service = new UserService($user);
        $service->deactivate();

        $user = User::find($user->id);
        $this->assertNull($user);

        $service->reactivate();

        $user = User::find($userId);
        $this->assertEquals($user->id, $userId);
    }
}
