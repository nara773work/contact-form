<?php

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

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\IndexController;
Route::get('contact',[IndexController::class,'index'])->middleware('auth');

use App\Http\Controllers\ConfirmController;
Route::post('contact/confirm',[ConfirmController::class,'store']);

use App\Http\Controllers\ThanksController;
Route::post('contact/thanks',[ThanksController::class,'index']);



