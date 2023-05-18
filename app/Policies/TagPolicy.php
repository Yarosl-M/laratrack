<?php

namespace App\Policies;

use App\Enums\UserType;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TagPolicy {
    public function view_any(User $user): bool {
        return $user->type === UserType::Operator->value || $user->type === UserType::Admin->value;
    }
    public function view(User $user, Tag $tag): bool {
        return $user->type === UserType::Operator->value || $user->type === UserType::Admin->value;
    }
    public function create(User $user): bool {
        return $user->hasPermission('edit_tags');
    }
    public function update(User $user, Tag $tag): bool {
        return $user->hasPermission('edit_tags');
    }
    public function delete(User $user, Tag $tag): bool {
        return $user->hasPermission('edit_tags');
    }
}
