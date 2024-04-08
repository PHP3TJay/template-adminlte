<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function manageSuperadmin(User $user)
    {
        return $user->hasRole('Superadmin');
    }

    public function manageAdmin(User $user)
    {
        return $user->hasRole('Admin');
    }

    public function manageManager(User $user)
    {
        return $user->hasRole('Manager');
    }

    public function manageTeamLeader(User $user)
    {
        return $user->hasRole('Team Leader');
    }

    public function manageQualityAssurance(User $user)
    {
        return $user->hasRole('Quality Assurance');
    }

    public function manageAgent(User $user)
    {
        return $user->hasRole('Agent');
    }
}
