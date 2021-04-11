<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\FileHospitalController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\OrderHospitalController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UserController;
use App\Models\FileHospital;
use App\Models\Hospital;
use App\Models\OrderHospital;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('/data/hospital', HospitalController::class);
Route::resource('/file/hospital', FileHospitalController::class);

Route::prefix('v1')->group(function() {
    Route::prefix('user')->group(function () {
        Route::post('login', [UserController::class, 'login']);
        Route::post('signup', [UserController::class, 'store']);
        Route::middleware('auth:user-api')->group(function() {
            Route::resource('/order/hospital', OrderHospitalController::class);
            Route::resource('/data', UserController::class);
        });
    });
    Route::prefix('admin')->group(function () {
        Route::post('login', [AdminController::class, 'login']);
        Route::middleware('auth:admin-api')->group(function() {
            Route::resource('/data', AdminController::class);
        });
    });
    Route::prefix('super/admin')->group(function () {
        Route::post('login', [SuperAdminController::class, 'login']);
        Route::middleware('auth:super_admin-api')->group(function() {
            Route::resource('/data', SuperAdminController::class);
        });
    });
});