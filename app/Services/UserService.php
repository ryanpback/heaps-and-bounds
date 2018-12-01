<?php

namespace App\Services;

use App\Models\User;

class UserService
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
     * User update
     *
     * @param array $data
     * @return User
     */
    public function update(array $data)
    {
        $this->user->update($data);

        return $this->user;
    }

    /**
     * Deactivate User
     *
     * @return bool
     */
    public function deactivate()
    {
        $deleted = $this->user->delete();

        return $deleted;
    }
}
