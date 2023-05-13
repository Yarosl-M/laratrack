<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticateRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
        // TODO: figure out where and how to validate
        $attr = $request->safe()->only('email', 'username', 'password', 'name');
        $attr['username'] = Str::lower($attr['username']);
        $u = $this->userService->create($attr);
        dd($u);
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

        public function update_pfp(Request $request) {
            $user = $request->user();
            $formFields = $request->validate([
                'pfp' => 'bail|required|mimes:png,jpg,jpeg|dimensions:min_width=50,min_height=50,max_width=1000,max_height=1000'
            ]);
            $filename = basename($request->file('pfp')->store('/public/users/' . $user->id));
            $user->profile_picture = $filename;
            $user->save();
            return redirect('/account');
        }

    public function authenticate(AuthenticateRequest $request) {
        $attr = $request->safe()->only('email', 'password');
        if ($this->userService->authenticate($attr)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }
        else return back()->withErrors(['auth' => 'Неправильные учётные данные']);
    }
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
