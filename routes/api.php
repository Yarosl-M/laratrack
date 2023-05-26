<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagApiController;
use App\Http\Controllers\UserApiController;
use App\Http\Controllers\TicketApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware('auth:sanctum')->group(function() {
    // leave a message in ticket
    Route::post('/tickets/{ticket}/comment', [TicketApiController::class, 'comment']);
    // get user dashboard card
    Route::get('/dashboard/users/{user}', [UserApiController::class, 'show']);
    // save user dashboard settings
    Route::post('/dashboard/users/{user}', [UserApiController::class, 'update']);
    // deactivate user
    Route::post('/dashboard/users/{user}/deactivate', [UserApiController::class, 'deactivate']);
    // reactivate user
    Route::post('/dashboard/users/{user}/activate', [UserApiController::class, 'activate']);
    // delete user
    Route::delete('/dashboard/users/{user}', [UserApiController::class, 'destroy']);
    // update tag
    Route::put('/dashboard/tags/{tag}', [TagApiController::class, 'update']);
    // create tag
    Route::post('/dashboard/tags', [TagApiController::class, 'store']);
    // delete tag
    Route::delete('/dashboard/tags/{tag}', [TagApiController::class, 'destroy']);
});