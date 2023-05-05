<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/test', function () {
    return view('users.login');
});

// получить форму входа
Route::get('/login', [UserController::class, 'login']);
// получить форму регистрации
Route::get('/register', [UserController::class, 'create']);
// зарегистрироваться
Route::post('/users', [UserController::class, 'store']);
// войти в систему
Route::post('/users/authenticate', [UserController::class, 'authenticate']);