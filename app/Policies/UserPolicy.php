<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy {
    public function view_any(User $user): bool {
        return $user->hasPermission('view_users');
    }
    public function view(User $user, User $model): bool {
        return $user->hasPermission('view_users');
    }
    public function change_permissions(User $user, User $model): bool {
        return $user->hasPermission('edit_user_permissions')
        && $user->id != $model->id;
    }
    public function deactivate(User $user, User $model): bool {
        return $user->hasPermission('deactivate_users')
        && $user->id != $model->id;
    }
    public function delete(User $user, User $model): bool {
        return $user->hasPermission('delete_users')
        && $user->id != $model->id;
    }
}
