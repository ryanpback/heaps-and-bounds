<?php

namespace App\Services\User;

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
        if ($this->user->active) {
            $this->user->active = false;
            $this->user->save();
        }

        return $this->user->delete();
    }

    /**
     * Restore User
     *
     * @return bool
     */
    public function reactivate()
    {
        if (!$this->user->active) {
            $this->user->active = true;
            $this->user->save();
        }

        return $this->user->restore();
    }
}
