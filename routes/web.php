<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MeetingRoomBookingController;

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


Route::get('users', [UserController::class, 'index'])->name('users');
Route::get('/', function () {
    return redirect('/login');
});
Route::get('/register', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::resource('rooms', RoomController::class);
Route::get('room-list', [RoomController::class, 'room_list'])->name('room-list');

Route::post('bookings-check', [MeetingRoomBookingController::class, 'booking_checks'])->name('bookings-check');
Route::resource('bookings', MeetingRoomBookingController::class);
