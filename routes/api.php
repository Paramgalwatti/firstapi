<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register',[ApiController::class,'register']);
Route::post('login',[ApiController::class,'login']);

Route::middleware('auth:sanctum')->group( function () {
    Route::get('get_user',[ApiController::class,'get_user']);
    Route::get('delete',[ApiController::class, 'delete']);
    Route::post('update', [ApiController::class, 'update']);
    
});