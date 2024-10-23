<?php

namespace App\Policies;

use App\Models\LeaveApplication;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeaveApplicationPolicy
{
    use HandlesAuthorization;

    // Allow admins and chief editors to edit any leave application
    public function editLeave(User $user, LeaveApplication $leaveApplication)
    {
        return $user->hasRole('admin') || $user->hasRole('chief editor');
    }
}
