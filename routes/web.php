<?php

use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\NewsController;
use App\Http\Controllers\API\SkinController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

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
Route::prefix('payments')->controller(PaymentController::class)->group(function () {
    Route::post('freekassa', 'freekassaSuccess');
});

Route::get('/skin/{any}/{string?}/{size?}', [SkinController::class, 'previewSkin']);

Route::fallback(fn () => view('app'));
