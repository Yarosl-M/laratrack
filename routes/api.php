<?php

use App\Http\Controllers\TicketApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')
->post('/tickets/{ticket}/comment', [TicketApiController::class, 'comment']);
// Route::post('/tickets/{ticket}/comment', function(Request $request) {return dd($request);});
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });