<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewList(User $user)
    {
        return $user->hasPermissionTo('users.viewList');
    }

    public function destroy(User $user)
    {
        return $user->hasPermissionTo('users.destroy');
    }

    public function restore(User $user)
    {
        return $user->hasPermissionTo('users.restore');
    }
}
