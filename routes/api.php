<?php

use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\DriverController;
use App\Http\Controllers\API\TankiController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('user', [UserController::class, 'fetch']);
    Route::post('user', [UserController::class, 'updateProfile']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::post('transactions/update', [TransactionController::class, 'update']);
    Route::put('update-fcm-token', [DriverController::class, 'update_fcm_token']);
    Route::post('paid/update', [TransactionController::class, 'update_paid_status']);

    Route::prefix('user')->group(function () {
        Route::get('profile', [UserController::class, 'profile']);
        Route::get('tangki',[TankiController::class,'all']);
        Route::get('address', [AddressController::class, 'all']);
        Route::post('address/add', [AddressController::class, 'add']);
        Route::post('address/update', [AddressController::class, 'update']);
        Route::get('transactions', [TransactionController::class, 'all']);
        Route::post('checkout', [TransactionController::class, 'checkout']);
        Route::post('review', [TransactionController::class, 'create_review']);
    });

    Route::prefix('driver')->group(function () {
        Route::get('transactions', [TransactionController::class, 'driver']);
        Route::get('update-active-status', [DriverController::class, 'update_active_status']);
        Route::get('profile', [DriverController::class, 'profile']);
        Route::post('tangki', [TankiController::class, 'register']);
        Route::post('tangki/update', [TankiController::class, 'updateTangki']);
        Route::post('record-location-data',  [DriverController::class, 'record_location_data']);
        Route::get('dashboard',  [DriverController::class, 'dashboardDriver']);
    });
});

Route::prefix('driver')->group(function () {
    Route::post('register', [DriverController::class, 'register']);
    Route::post('login', [DriverController::class, 'login']);
});

Route::prefix('user')->group(function () {
    Route::post('register', [UserController::class, 'register']);
    Route::post('login', [UserController::class, 'login']);
});
