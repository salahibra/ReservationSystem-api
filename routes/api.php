<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PassportAuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Middleware\CheckRole;

Route::post('register', [PassportAuthController::class, 'register']);
Route::post('login', [PassportAuthController::class, 'login']);




// route middleware to check if user is admin and authenticated
Route::middleware(['auth:api', CheckRole::class . ':admin'])->group(function () {
    // get all users
    Route::get('admin/users', [PassportAuthController::class, 'getUsers']);
    // get userDetails
    Route::get('admin/userDetails', [PassportAuthController::class, 'userDetails']);
    // get all reservations
    Route::get('admin/reservations', [ReservationController::class, 'index']);
    // get reservation by id
    Route::resource('admin/reservations', ReservationController::class)->only([
        'show'
    ]);
    // get reservations by user id
    Route::get('admin/reservations/student/{id}', [ReservationController::class, 'getReservationsByUser']);

    // get student by reservation id
    Route::get('admin/reservation/{id}/student', [ReservationController::class, 'getUserByReservation']);

    // get the number of reservations by user id
    Route::get('admin/reservations/student/{id}/count', [ReservationController::class, 'getNumberOfReservationsByUser']);
});





// route middleware to check if user is student and authenticated
Route::middleware(['auth:api', CheckRole::class . ':student'])->group(function () {
    // get userDetails
    Route::get('student/userDetails', [PassportAuthController::class, 'userDetails']);
    // CRUD reservation: get reservation by id, store, update, delete
    Route::resource('student/reservation', ReservationController::class)->only([
        'show', 'store', 'update', 'destroy'
    ]);

    // get reservations by user id
    Route::get('student/{id}/reservations/', [ReservationController::class, 'getReservationsByUser']);
    // get available pcs by date and time slot and handle date and time slot are by default null
    Route::get('student/available-pcs/{date?}/{timeSlot?}', [ReservationController::class, 'getAvailablePcs']);
    // get qr code
    Route::get('student/reservation/{id}/qr-code', [ReservationController::class, 'generateQrCode']);
    // get the number of reservations by user id
    Route::get('student/reservations/count', [ReservationController::class, 'getNumberOfReservationsByUser']);

    // get time slots available for a student in a specific date function getTimeSlotAvailableForUser
    Route::get('student/{id}/time-slots/{date}', [ReservationController::class, 'getTimeSlotAvailableForUser']);
});
