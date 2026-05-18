<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AdminPolicy
{
    public function before(User $user, string $ability): ?bool{
        dd('TagPolicy before called');
    if ($user->id===1) {
        return true;
    }
     return null;
    }
}

