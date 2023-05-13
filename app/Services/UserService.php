<?php 

namespace App\Services;

use App\Enums\UserType;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function authenticate(array $credentials): bool { return auth()->attempt($credentials); }

    public function changePassword(User $user, string $new): bool {
        $oldHash = $user->password;
        // old and new are the same
        if (Hash::check($new, $oldHash)) return false;
        $hash = Hash::make($new);
        $user->password = $hash;
        $user->save();
        return true;
    }
}