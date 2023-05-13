<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Requests\CreateTicketRequest;
use Illuminate\Http\Request;
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

// for now
Route::get('/', [TicketController::class, 'index']);

Route::get('/tickets', [TicketController::class, 'index']);

Route::get('/tickets/archive', [TicketController::class, 'archive']);

// форма создания тикета
Route::get('/tickets/create', [TicketController::class, 'create']);
// страница тикета
Route::get('/tickets/{ticket}', [TicketController::class, 'show']);

// открыть параметры тикета
Route::get('/tickets/{ticket}/settings', [TicketController::class, 'settings']);
// сохранить параметры тикета
Route::post('/tickets/{ticket}/settings', [TicketController::class, 'update']);

Route::post('/tickets/{ticket}/comment', [TicketController::class, 'comment']);

Route::post('/tickets', [TicketController::class, 'store']);

// получить форму входа
Route::get('/login', [UserController::class, 'login']);
// получить форму регистрации
Route::get('/register', [UserController::class, 'create']);

Route::post('/logout', [UserController::class, 'logout']);
// зарегистрироваться
Route::post('/users', [UserController::class, 'store']);
// войти в систему
Route::post('/users/authenticate', [UserController::class, 'authenticate']);

Route::get('/account', [UserController::class, 'edit']);
Route::get('/account/change-password', [UserController::class, 'change_password']);

Route::post('/account/update-profile-picture', [UserController::class, 'update_pfp']);
Route::post('/users/change-password', [UserController::class, 'update_password']);