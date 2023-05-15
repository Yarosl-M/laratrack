<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use App\Http\Requests\CreateTicketRequest;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
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
// for now
Route::get('/', [TicketController::class, 'index']);

Route::get('/test', function () {
    return view('users.login');
});

Route::middleware('guest')->group(function() {
    // получить форму входа
    Route::get('/login', [UserController::class, 'login'])->name('login');
    // получить форму регистрации
    Route::get('/register', [UserController::class, 'create']);
    // зарегистрироваться
    Route::post('/users', [UserController::class, 'store']);
    // войти в систему
    Route::post('/users/authenticate', [UserController::class, 'authenticate']);
});

// только для авторизованных пользователей
Route::middleware('auth')->group(function() {
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

    Route::post('/tickets/{ticket}/archive', [TicketController::class, 'move_to_archive']);
    Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy']);

    Route::post('/tickets', [TicketController::class, 'store']);

    Route::post('/logout', [UserController::class, 'logout']);

    Route::get('/account', [UserController::class, 'edit']);
    Route::get('/account/change-password', [UserController::class, 'change_password']);

    Route::post('/account/update', [UserController::class, 'update']);
    Route::post('/account/update-profile-picture', [UserController::class, 'update_pfp']);
    Route::post('/users/change-password', [UserController::class, 'update_password']);

    Route::get('/dashboard', [UserController::class, 'dashboard']);

    // email verification
    // Route::get('/verify-email', [UserController::class, 'verification_notice'])
    // ->name('verification-notice');
    // // send verification link
    // Route::get('email/verify/{id}/{hash}', function(EmailVerificationRequest $request) {
    //     $request->fulfill();
    //     return redirect('/');
    // })->middleware('signed')->name('verification.verify');
    // resend link
    // Route::post('/email/verification-notification', function(Request $request) {
    //     $request->user()->sendEmailVerificationNotification();
    //     return back()->with('message', 'Ссылка для подтверждения отправлена');
    // });
});
