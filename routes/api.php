<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangSatuanController;
use App\Http\Controllers\BarangSatuanJadiController;
use App\Http\Controllers\BarangKategoriController;
use App\Http\Controllers\BarangMentahController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserGroupController;

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

Route::prefix('v1')->group(function () {
    Route::prefix('secure')->group(function () {
        Route::post('check-credential', [AuthController::class, 'checkCredential']);
        Route::post('get-otp', [AuthController::class, 'getOtp']);
    });

    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);

        Route::middleware('auth:api')->group(function () {
            Route::get('refresh', [AuthController::class, 'refresh']);
            Route::get('user', [AuthController::class, 'user']);
            Route::post('logout', [AuthController::class, 'logout']);
        });
    });

    Route::middleware(['auth:api', 'level:admin'])->group(function () {
        Route::prefix('dashboard')->group(function () {
            Route::prefix('menu')->group(function () {
                Route::get('', [DashboardController::class, 'menu']);
                Route::post('access', [DashboardController::class, 'menuAccess']);
                Route::post('access/save', [DashboardController::class, 'menuAccessSave']);
                Route::post('access/checked', [DashboardController::class, 'menuAccessChecked']);

                Route::post('has-access', [DashboardController::class, 'hasAccess']);
            });
        });
        Route::prefix('user')->group(function () {
            Route::post('paginate', [UserController::class, 'paginate']);
            Route::post('store', [UserController::class, 'store']);
            Route::get('{uuid}/edit', [UserController::class, 'edit']);
            Route::post('{uuid}/update', [UserController::class, 'update']);
            Route::delete('{uuid}/destroy', [UserController::class, 'destroy']);
        });
        Route::prefix('user-group')->group(function () {
            Route::get('get', [UserGroupController::class, 'get']);
            Route::get('{uuid}', [UserGroupController::class, 'show']);
            Route::post('paginate', [UserGroupController::class, 'paginate']);
            Route::post('store', [UserGroupController::class, 'store']);
            Route::get('{uuid}/edit', [UserGroupController::class, 'edit']);
            Route::post('{uuid}/update', [UserGroupController::class, 'update']);
            Route::delete('{uuid}/destroy', [UserGroupController::class, 'destroy']);
        });
        Route::prefix('position')->group(function () {
            Route::get('get', [PositionController::class, 'get']);
            Route::post('paginate', [PositionController::class, 'paginate']);
            Route::post('store', [PositionController::class, 'store']);
            Route::get('{uuid}/edit', [PositionController::class, 'edit']);
            Route::post('{uuid}/update', [PositionController::class, 'update']);
            Route::delete('{uuid}/destroy', [PositionController::class, 'destroy']);
        });
        Route::prefix('barangsatuan')->group(function () {
            Route::get('get', [BarangSatuanController::class, 'get']);
            Route::post('paginate', [BarangSatuanController::class, 'paginate']);
            Route::post('store', [BarangSatuanController::class, 'store']);
            Route::get('{uuid}/edit', [BarangSatuanController::class, 'edit']);
            Route::get('{id}/child', [BarangSatuanController::class, 'child']);
            Route::post('{uuid}/update', [BarangSatuanController::class, 'update']);
            Route::delete('{uuid}/destroy', [BarangSatuanController::class, 'destroy']);
        });

        Route::prefix('barangsatuanjadi')->group(function () {
            Route::get('get', [BarangSatuanJadiController::class, 'get']);
            Route::post('paginate', [BarangSatuanJadiController::class, 'paginate']);
            Route::post('store', [BarangSatuanJadiController::class, 'store']);
            Route::get('{uuid}/edit', [BarangSatuanJadiController::class, 'edit']);
            Route::post('{uuid}/update', [BarangSatuanJadiController::class, 'update']);
            Route::delete('{uuid}/destroy', [BarangSatuanJadiController::class, 'destroy']);
        });

        Route::prefix('kategori')->group(function () {
            Route::get('get', [KategoriController::class, 'get']);
            Route::post('paginate', [KategoriController::class, 'paginate']);
            Route::post('store', [KategoriController::class, 'store']);
            Route::get('{uuid}/edit', [KategoriController::class, 'edit']);
            Route::post('{uuid}/update', [KategoriController::class, 'update']);
            Route::delete('{uuid}/destroy', [KategoriController::class, 'destroy']);
        });

        Route::prefix('barangmentah')->group(function () {
            Route::get('get', [BarangMentahController::class, 'get']);
            Route::post('paginate', [BarangMentahController::class, 'paginate']);
            Route::post('store', [BarangMentahController::class, 'store']);
            Route::get('{uuid}/edit', [BarangMentahController::class, 'edit']);
            Route::get('{id}/child', [BarangMentahController::class, 'child']);
            Route::post('{uuid}/update', [BarangMentahController::class, 'update']);
            Route::delete('{uuid}/destroy', [BarangMentahController::class, 'destroy']);
        });
    });
});
