<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy {
    public function viewAny(User $user): bool {
        return $user->hasPermission('view_users');
    }
    public function view(User $user, User $model): bool {
        return $user->hasPermission('view_users');
    }
    public function change_permissions(User $user, User $model): bool {
        return $user->hasPermission('edit_user_permissions');
    }
    public function deactivate(User $user, User $model): bool {
        return $user->hasPermission('deactivate_users');
    }
    public function delete(User $user, User $model): bool {
        return $user->hasPermission('delete_users');
    }
}
