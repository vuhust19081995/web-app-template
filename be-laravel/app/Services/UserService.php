<?php

namespace App\Services;

use App\Models\User;

class UserService extends BaseService
{

    public function model(): mixed {
        return User::class;
    }
}
