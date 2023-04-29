<?php 

namespace App\Services;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService {
    public function __construct(private PermissionService $permissionService) {}

    public function create(array $createUser): User {
        $u = new User();

        $u->username = $createUser['username'];
        $u->email = $createUser['email'];

        $hash = Hash::make($createUser['password']);

        $u->password = $hash;

        $u->type = UserType::Client->value;

        $u->save();
        return $u;
    }

    public function authenticate(array $credentials): bool {
        return auth()->attempt($credentials);
    }
}