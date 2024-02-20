<?php

use App\Http\Controllers\API\CoreController;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\NewsController;
use App\Http\Controllers\API\ShopController;
use App\Http\Controllers\API\SkinController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\LoaderController;
use App\Http\Controllers\API\ReplenishmentController;
use Illuminate\Http\Request;
use App\Http\Controllers\PaymentController;
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

// Route::middleware('auth:sanctum')->group(function () {});

Route::any('loader/{target}/{version}', [LoaderController::class, 'checkUpdates']);

Route::prefix('auth')->middleware('guest')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('login/totp', [LoginController::class, 'loginTotp']);
    Route::post('register', [RegisterController::class, 'register']);
});

Route::prefix('core')->group(function () {
    Route::get('auth', [CoreController::class, 'auth']);
    Route::get('load', [CoreController::class, 'loadInfo']);
    Route::get('hd', [CoreController::class, 'loadHD']);
});

Route::prefix('user')->middleware('auth')->group(function () {
    Route::get('activities/auth', [UserController::class,'activities']);
    Route::post('teleport', [UserController::class,'teleport']);
    Route::post('change', [UserController::class,'change']);

    Route::post('replenishment', [PaymentController::class,'replenishment']);

    Route::post('exchange', [UserController::class,'exchange']);
    Route::post('code/activate', [UserController::class,'codeActivate']);

    Route::prefix('totp')->group(function () {
        Route::get('load', [UserController::class, 'totpLoad']);
        Route::post('enable', [UserController::class, 'totpEnable']);
        Route::post('disable', [UserController::class, 'totpDisable']);
    });

    Route::prefix('buy')->group(function () {
        Route::post('hd', [UserController::class, 'buyHD']);
        Route::post('skin', [UserController::class, 'buySkin']);
        Route::post('cloak', [UserController::class, 'buyCloak']);
    });
});

Route::prefix('upload')->middleware('auth')->group(function () {
    Route::post('skin', [SkinController::class,'uploadSkin']);
    Route::post('cloak', [SkinController::class,'uploadCloak']);
});

Route::prefix('delete')->middleware('auth')->group(function () {
    Route::post('skin', [SkinController::class,'deleteSkin']);
    Route::post('cloak', [SkinController::class,'deleteCloak']);
});

Route::prefix('shop')->middleware('auth')->group(function () {
    Route::post('load', [ShopController::class,'load']);

    Route::prefix('buy')->group(function () {
        Route::post('donate', [ShopController::class, 'buyDonate']);
        Route::post('item', [ShopController::class, 'buyItem']);
        Route::post('block', [ShopController::class, 'buyBlock']);
    });
});

Route::get('logout', [LoginController::class, 'logout']);

Route::get('news/{id}', [NewsController::class, 'show'])->whereNumber('id');
