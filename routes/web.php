<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\TicketController;
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

// форма создания тикета
Route::get('/tickets/create', [TicketController::class, 'create']);

Route::get('/tickets/{ticket}', [TicketController::class, 'show']);

// прикреплённые к сообщениям файлы
Route::get('/files/tickets/{ticket_id}/{message_id}/{file}', [FileController::class, 'getTicketAttachment']);

Route::get('/files/users/{user_id}/{file}', [FileController::class, 'getProfilePicture']);

// получить форму входа
Route::get('/login', [UserController::class, 'login']);
// получить форму регистрации
Route::get('/register', [UserController::class, 'create']);
// зарегистрироваться
Route::post('/users', [UserController::class, 'store']);
// войти в систему
Route::post('/users/authenticate', [UserController::class, 'authenticate']);