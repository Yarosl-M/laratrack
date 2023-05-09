<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticateRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
