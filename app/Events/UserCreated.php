<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;

class UserCreated
{
    use Dispatchable;

    public $userMysql;
    public $userFirebase;

    public function __construct(User $userMysql, $userFirebase)
    {
        $this->userMysql = $userMysql;
        $this->userFirebase = $userFirebase;
    }
}
