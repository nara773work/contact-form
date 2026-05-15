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
    return redirect('/contact');
});

use App\Http\Controllers\ContactController;
Route::get('contact',[ContactController::class,'index'])->middleware('auth');
Route::post('contact/confirm',[ContactController::class,'confirm']);
Route::post('/thanks',[ContactController::class,'thanks']);

use App\Http\Controllers\AdminController;
Route::get('admin',[AdminController::class,'index']);
Route::get('admin/contacts/{contact}',[AdminController::class,'show']);
Route::get('admin/tags/{tag}/edit',[AdminController::class,'edit']);
Route::delete('admin/tags/{tag}',[AdminController::class,'destroy']);