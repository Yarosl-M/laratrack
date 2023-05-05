<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticateRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    public function create() {
        return view('users.register');
    }

    public function login() {
        return view('users.login');
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
            return 'Successfully signed in';
        }
        else return 'Fail';
    }
}
