<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Models\Permission;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class UserApiController extends Controller
{
    public function __construct(private UserService $userService) {}
    public function show(Request $request, User $user) {
        $this->authorize('view', $user);
        // $userArr = $user->only(['id', 'username', 'profile_picture', 'deactivated_at', 'type', 'name']);
        // $userArr['display_name'] = $user->displayName();
        // $userArr['permissions'] = $user->permissions()->get()->pluck('id')->toArray();
        
        $component = view('components.user-dashboard-card', [
            'user' => $user, 'permissions' => Permission::get()
        ]);
        $html = $component->render();
        return response()->json([
            'html' => $html
        ]);
    }
    public function update(Request $request, User $user) {
        $this->authorize('change_permissions', $user);
        $validated = $request->validate([
            'type' => [new Enum(UserType::class)]
        ]);
        
        $permissionIds = $request->except('type');
        $type = UserType::tryFrom($validated['type']);
        $this->userService->setPermissions($user, $permissionIds);
        $this->userService->setType($user, $type);
        return response()->json([
            'message' => 'Настройки пользователя успешно обновлены'
        ]);
    }

    public function deactivate(User $user) {
        $this->authorize('deactivate', $user);
        $u = $this->userService->deactivate($user);
        $component = view('components.user-dashboard-card', [
            'user' => $u, 'permissions' => Permission::get()
        ]);
        $html = $component->render();

        return response()->json([
            'message' => 'Учётная запись успешно отключена',
            'html' => $html
        ]);
    }

    public function activate(User $user) {
        $this->authorize('deactivate', $user);
        $u = $this->userService->activate($user);
        $component = view('components.user-dashboard-card', [
            'user' => $u, 'permissions' => Permission::get()
        ]);
        return response()->json([
            'message' => 'Учётная запись успешно активирована',
            'html' => $component->render()
        ]);
    }
    public function destroy(User $user) {
        $this->authorize('delete', $user);
        $user->delete();
        return response()->json(['message' => 'Учётная запись успешно удалена']);
    }
}
