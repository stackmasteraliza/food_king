<?php

namespace App\Policies;

use APP\Models\User;
use APP\Models\OperationLog;

class OperationLogPolicy
{
    public function viewAny(User $user)
    {
        return $user->isAdmin();
    }

    public function markSeen(User $user)
    {
        return $user->hasPermission('monitor_activities');
    }
    public function view(User $user, OperationLog $log)
    {
        return $user->isAdmin() || $user->id === $log->user_id;
    }
}
