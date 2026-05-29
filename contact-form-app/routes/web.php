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

// contact関連
use App\Http\Controllers\ContactController;

Route::get('contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('contact/confirm', [ContactController::class, 'confirm'])->name('contact.confirm');
Route::post('contact/store', [ContactController::class, 'store'])->name('contact.store');
Route::get('/thanks', [ContactController::class, 'thanks'])->name('contact.thanks');

// admin関連
use App\Http\Controllers\AdminController;

Route::get('admin', [AdminController::class, 'index'])->name('admin.index')->middleware('auth'); // 管理画面一覧
Route::get('admin/contacts/{contact}', [AdminController::class, 'show'])->name('admin.show')->middleware('auth'); // 
Route::get('contacts/export', [AdminController::class, 'export'])->middleware('auth');

// タグ関連
Route::get('admin/tags/{tag}/edit', [AdminController::class, 'edit'])->name('admin.edit')->middleware('auth'); // タグの編集画面
Route::post('admin/tags', [AdminController::class, 'store'])->name('admin.edit.post')->middleware('auth'); // タグの新規作成
Route::put('admin/tags/{tag}', [AdminController::class, 'update'])->name('admin.put')->middleware('auth');
Route::delete('admin/tags/{tag}', [AdminController::class, 'destroyTag'])->name('admin.tags.delete')->middleware('auth');
Route::delete('admin/contacts/{contact}', [AdminController::class, 'destroyContact'])->name('admin.contacts.delete')->middleware('auth');
