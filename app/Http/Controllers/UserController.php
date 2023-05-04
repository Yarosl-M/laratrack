<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthenticateRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private UserService $userService) {}

    public function create() {
        return view('users.register');
    }

    public function store(Request $request) {
        // TODO: figure out where and how to validate
        $formFields = $request->only(['email', 'username', 'password', 'password_confirmation', 'name']);
    }

    public function authenticate(AuthenticateRequest $request) {
        $attr = $request->safe()->only('email', 'password');
        if ($this->userService->authenticate($attr)) {
            return 'Successfully signed in';
        }
        else return 'Fail';
    }
}
