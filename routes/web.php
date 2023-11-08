<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ImageController;



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

Route::controller(LoginRegisterController::class)->group(function() {
    Route::get('/register', 'register')->name('register');
    Route::post('/store', 'store')->name('store');
    Route::get('/login', 'login')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::post('/logout', 'logout')->name('logout');
});

Route::get('/users', [ImageController::class, 'showUsers'])->name('users');
Route::get('/users/{id}/edit', [ImageController::class, 'edit'])->name('edit');
Route::put('/users/{id}', [ImageController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [ImageController::class, 'destroy'])->name('users.delete');
Route::get('/users/{user}/resize', [ImageController::class, 'resizeForm'])->name('users.resize');
Route::post('/users/{user}/resize', [ImageController::class, 'resizeImage'])->name('resizeImage');




Route::get('/create-file', [FileController::class, 'createFile']);
Route::get('/get-file', [FileController::class, 'getFile']);
Route::get('/download-file', [FileController::class, 'downloadFile']);
Route::get('/copy-file', [FileController::class, 'copyFile']);
Route::get('/move-file', [FileController::class, 'moveFile']);
