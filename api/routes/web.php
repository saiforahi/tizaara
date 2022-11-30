<?php

use App\Http\Controllers\User\PaymentController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//To clear all cache
Route::get('cc', function () {
    Artisan::call('optimize:clear');
    return "Cleared!";
});
Route::get('/', function () {
    return view('welcome');
});
Route::get('/shurjo', [PaymentController::class,'shurjopay_checkout']);
Route::get('/check/{id}', [PaymentController::class,'shurjopay_check']);