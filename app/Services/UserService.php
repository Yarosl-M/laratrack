<?php 

namespace App\Services;

use App\Enums\UserType;
use App\Models\Permission;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService {
    public function __construct(private PermissionService $permissionService) {}

    public function create(array $createUser): User {
        $u = new User();

        $u->username = $createUser['username'];
        $u->email = $createUser['email'];

        if (isset($createUser['name']) && !empty($createUser['name'])) $u->name = $createUser['name'];

        $hash = Hash::make($createUser['password']);

        $u->password = $hash;

        $u->type = UserType::Client->value;

        $u->save();

        return $u;
    }

    public function authenticate(string $email, string $password): bool { return Auth::attempt([
        'email' => $email, 'password' => $password
    ]); }

    public function changePassword(User $user, string $new): bool {
        $oldHash = $user->password;
        // old and new are the same
        if (Hash::check($new, $oldHash)) return false;
        $hash = Hash::make($new);
        $user->password = $hash;
        $user->save();
        return true;
    }

    public function changeAccountSettings(User $user, string|null $name, string $email) {
        $user->name = $name;
        $user->email = $email;
        $user->save();
        // ???
        return $user;
    }

    public function setType(User $user, UserType $type): User {
        $user->type = $type->value;
        $user->save();
        return $user;
    }

    public function setPermissions(User $user, array $ids): User {
        $user->permissions()->sync($ids);
        $user->save();
        return $user;
    }

    public function deactivate(User $user): User {
        if ($user->deactivated_at) return $user;

        $user->deactivated_at = Carbon::now();
        $user->permissions()->detach();
        $filename = $user->profile_picture;
        if (isset($filename)) {
            Storage::delete('/public/users/' . $filename);
        }
        $user->save();
        return $user;
    }

    public function activate(User $user): User {
        if ($user->deactivated_at == null) return $user;

        $user->deactivated_at = null;
        $user->save();
        return $user;
    }
}