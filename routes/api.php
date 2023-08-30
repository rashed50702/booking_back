<?php

use App\Http\Controllers\Api\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterController;

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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function(Request $request){
        return $request->user();
    });
    Route::post('/check-bookings', [BookingController::class, 'check']);
    Route::post('/book-now', [BookingController::class, 'book_now']);
    
});

Route::post('register', [RegisterController::class, 'register']); // Signup
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout']);