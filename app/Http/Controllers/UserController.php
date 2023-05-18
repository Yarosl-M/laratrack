<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\AuthenticateRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ChangeAccountSettingsRequest;
use App\Models\Permission;
use App\Models\Tag;

class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    public function create() {
        return view('users.register', ['sheets' => ['style_form'], 'title' => 'Регистрация']);
    }

    public function login() {
        return view('users.login', ['sheets' => ['style_form'], 'title' => 'Войти']);
    }

    public function store(RegisterRequest $request) {
            $attr = $request->safe()->only('email', 'username', 'password', 'name');
            $attr['username'] = Str::lower($attr['username']);
            $u = $this->userService->create($attr);
            Auth::login($u);
            return redirect('/login');
        }

    public function edit(Request $request) {
        return view('users.edit', ['user' => $request->user()]);
    }
    public function change_password(Request $request) {
        return view('users.change-password', ['user' => $request->user()]);
    }

    public function update_password(ChangePasswordRequest $request) {
        $attr = $request->safe()->only('current_password', 'password');
        $u = $request->user();
        $oldHash = $u->password;
        if (Hash::check($attr['current_password'], $oldHash)) {
            if ($this->userService->changePassword($u, $attr['password'])) {
                return redirect('/account');
            }
            else return back()->withErrors(['pwd_change' => 'Новый пароль должен отличаться от старого']);
        }
        else return back()->withErrors(['pwd_change' => 'Неверно указан текущий пароль']);
    }

    // these ones are for updating self, updating other users is in api controller
    public function update(Request $request) {
        $u = $request->user();
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|max:100',
            'email' => ['bail', 'required', 'email', Rule::unique('users')->ignore($u->id)]
        ]);
        if ($validator->fails()) {
            return redirect('/account')->withErrors($validator);
        }
        $attr = $validator->validated();
        $this->userService->changeAccountSettings($u, $attr['name'], $attr['email']);
        return redirect('/account');
    }

    public function update_pfp(Request $request) {
        $user = $request->user();
        $formFields = $request->validate([
            'pfp' => 'bail|required|max:3072|mimes:png,jpg,jpeg|dimensions:min_width=50,min_height=50,max_width=1000,max_height=1000'
        ]);
        $filename = basename($request->file('pfp')->store('/public/users/' . $user->id));
        $user->profile_picture = $filename;
        $user->save();
        return redirect('/account');
    }

    public function authenticate(AuthenticateRequest $request) {
        $attr = $request->safe()->only('email', 'password');
        if ($this->userService->authenticate($attr['email'], $attr['password'])) {
            if (!is_null(Auth::user()->deactivated_at)) {
                Auth::logout(); $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->withErrors(['auth' => 'Учётная запись отключена.']);
            }
            $request->session()->regenerate();
            return redirect()->intended('/');
        }
        return back()->withErrors(['auth' => 'Неправильные учётные данные']);
    }
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    
    public function dashboard(Request $request) {
        $user = $request->user();
        $this->authorize('change_permissions', $user);
        $tags = Tag::get();
        $users = User::get();
        $permissions = Permission::get();
        return view('users.dashboard', compact('user', 'tags', 'users', 'permissions'));
    }
}
