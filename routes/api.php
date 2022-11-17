<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangJadiController;
use App\Http\Controllers\BarangSatuanController;
use App\Http\Controllers\BarangSatuanJadiController;
use App\Http\Controllers\BarangKategoriController;
use App\Http\Controllers\BarangMentahController;
use App\Http\Controllers\BarangProduksiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GudangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserGroupController;
use App\Http\Controllers\AccountController;

use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProvinsiController;
use App\Http\Controllers\KotaController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KelurahanController;
use App\Http\Controllers\ProfileController;

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
        // Route::prefix('master_migration')->group(function(){
		// 	Route::prefix('account')->group(function(){
		// 		Route::get('show', 'AccountController@show');
		// 		Route::group(['middleware' => ['admin.access']], function() {
		// 			Route::post('index', 'AccountController@index');
		// 			Route::post('create', 'AccountController@store');
		// 			Route::get('{uuid}/edit', 'AccountController@edit');
		// 			Route::post('{uuid}/update', 'AccountController@update');
		// 			Route::delete('{uuid}/delete', 'AccountController@destroy');
		// 		});
		// 	});
        // });

        Route::prefix('account')->group(function () {
            Route::get('get', [AccountController::class, 'get']);
            Route::get('getdata', [AccountController::class, 'getdata']);
            Route::post('paginate', [AccountController::class, 'paginate']);
            Route::post('store', [AccountController::class, 'store']);
            Route::post('send', [AccountController::class, 'send']);
            Route::get('{uuid}/edit', [AccountController::class, 'edit']);
            Route::post('{uuid}/update', [AccountController::class, 'update']);
            Route::delete('{uuid}/destroy', [AccountController::class, 'destroy']);

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

        Route::prefix('supplier')->group(function () {
            Route::get('get', [SupplierController::class, 'get']);
            Route::post('paginate', [SupplierController::class, 'paginate']);
            Route::post('store', [SupplierController::class, 'store']);
            Route::get('{uuid}/edit', [SupplierController::class, 'edit']);
            Route::post('{uuid}/update', [SupplierController::class, 'update']);
            Route::delete('{uuid}/destroy', [SupplierController::class, 'destroy']);
            Route::get('getcode', [SupplierController::class, 'getcode']);
        });  
        Route::prefix('customer')->group(function () {
            Route::get('get', [CustomerController::class, 'get']);
            Route::post('paginate', [CustomerController::class, 'paginate']);
            Route::post('store', [CustomerController::class, 'store']);
            Route::get('{uuid}/edit', [CustomerController::class, 'edit']);
            Route::post('{uuid}/update', [CustomerController::class, 'update']);
            Route::delete('{uuid}/destroy', [CustomerController::class, 'destroy']);
        });

        Route::prefix('provinsi')->group(function () {
            Route::get('get', [ProvinsiController::class, 'get']);
            Route::post('paginate', [ProvinsiController::class, 'paginate']);
            Route::post('store', [ProvinsiController::class, 'store']);
            Route::get('{uuid}/edit', [ProvinsiController::class, 'edit']);
            Route::post('{uuid}/update', [ProvinsiController::class, 'update']);
            Route::delete('{uuid}/destroy', [ProvinsiController::class, 'destroy']);
        });
        Route::prefix('kota')->group(function () {
            Route::get('get', [KotaController::class, 'get']);
            Route::post('paginate', [KotaController::class, 'paginate']);
            Route::post('store', [KotaController::class, 'store']);
            Route::get('{uuid}/edit', [KotaController::class, 'edit']);
            Route::post('{uuid}/update', [KotaController::class, 'update']);
            Route::delete('{uuid}/destroy', [KotaController::class, 'destroy']);
        });
        Route::prefix('kecamatan')->group(function () {
            Route::get('get', [KecamatanController::class, 'get']);
            Route::post('paginate', [KecamatanController::class, 'paginate']);
            Route::post('store', [KecamatanController::class, 'store']);
            Route::get('{uuid}/edit', [KecamatanController::class, 'edit']);
            Route::post('{uuid}/update', [KecamatanController::class, 'update']);
            Route::delete('{uuid}/destroy', [KecamatanController::class, 'destroy']);
        });
        Route::prefix('kelurahan')->group(function () {
            Route::get('get', [KelurahanController::class, 'get']);
            Route::post('paginate', [KelurahanController::class, 'paginate']);
            Route::post('store', [KelurahanController::class, 'store']);
            Route::get('{uuid}/edit', [KelurahanController::class, 'edit']);
            Route::post('{uuid}/update', [KelurahanController::class, 'update']);
            Route::delete('{uuid}/destroy', [KelurahanController::class, 'destroy']);
        });

        Route::prefix('profile')->group(function () {
            Route::get('get', [ProfileController::class, 'get']);
            Route::post('paginate', [ProfileController::class, 'paginate']);
            Route::post('store', [ProfileController::class, 'store']);
            Route::get('{uuid}/edit', [ProfileController::class, 'edit']);
            Route::post('{uuid}/update', [ProfileController::class, 'update']);
            Route::delete('{uuid}/destroy', [ProfileController::class, 'destroy']);
        });
            
        Route::prefix('kategori')->group(function () {
            Route::get('get', [KategoriController::class, 'get']);
            Route::post('paginate', [KategoriController::class, 'paginate']);
            Route::post('store', [KategoriController::class, 'store']);
            Route::get('{uuid}/edit', [KategoriController::class, 'edit']);
            Route::post('{uuid}/update', [KategoriController::class, 'update']);
            Route::delete('{uuid}/destroy', [KategoriController::class, 'destroy']);
        });

        Route::prefix('gudang')->group(function () {
            Route::get('get', [GudangController::class, 'get']);
            Route::post('paginate', [GudangController::class, 'paginate']);
            Route::post('store', [GudangController::class, 'store']);
            Route::get('getcode', [GudangController::class, 'getcode']);
            Route::get('{uuid}/edit', [GudangController::class, 'edit']);
            Route::post('{uuid}/update', [GudangController::class, 'update']);
            Route::delete('{uuid}/destroy', [GudangController::class, 'destroy']);
        });
        
        Route::prefix('barangmentah')->group(function () {
            Route::get('get', [BarangMentahController::class, 'get']);
            Route::post('paginate', [BarangMentahController::class, 'paginate']);
            Route::post('store', [BarangMentahController::class, 'store']);
            Route::get('getcode', [BarangMentahController::class, 'getcode']);
            Route::get('{id}/getcode', [BarangMentahController::class, 'getcodebyid']);
            Route::get('{uuid}/edit', [BarangMentahController::class, 'edit']);
            Route::get('{id}/child', [BarangMentahController::class, 'child']);
            Route::post('{uuid}/update', [BarangMentahController::class, 'update']);
            Route::delete('{uuid}/destroy', [BarangMentahController::class, 'destroy']);
        });

        Route::prefix('barangjadi')->group(function () {
            Route::get('get', [BarangJadiController::class, 'get']);
            Route::post('paginate', [BarangJadiController::class, 'paginate']);
            Route::post('store', [BarangJadiController::class, 'store']);
            Route::get('getcode', [BarangJadiController::class, 'getcode']);
            Route::get('{id}/getcode', [BarangJadiController::class, 'getcodebyid']);
            Route::get('{uuid}/edit', [BarangJadiController::class, 'edit']);
            Route::post('{uuid}/update', [BarangJadiController::class, 'update']);
            Route::delete('{uuid}/destroy', [BarangJadiController::class, 'destroy']);
        });

        Route::prefix('barangproduksi')->group(function () {
            Route::get('get', [BarangProduksiController::class, 'get']);
            Route::post('paginate', [BarangProduksiController::class, 'paginate']);
            Route::post('store', [BarangProduksiController::class, 'store']);
            Route::get('{uuid}/edit', [BarangProduksiController::class, 'edit']);
            Route::post('{uuid}/update', [BarangProduksiController::class, 'update']);
            Route::delete('{uuid}/destroy', [BarangProduksiController::class, 'destroy']);
        });

    });
});
