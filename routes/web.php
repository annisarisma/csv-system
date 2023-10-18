<?php

use App\Http\Controllers\CsvController;
use App\Http\Controllers\UserController;
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

Route::controller(UserController::class)->group(function () {
    Route::get('/register', 'register_index');
    Route::post('/register/store', 'register_store');
    Route::get('/login', 'login_index');
    Route::post('/login/store', 'login_store');
});
Route::controller(CsvController::class)->group(function () {
    Route::get('/csv-create', 'create');
    Route::get('/csv-create/real-time', 'real_time');
    Route::post('/csv-store', 'store');
    Route::post('/csv-file/temporary', 'temporary_store')->name('csv-upload');
    Route::delete('/csv-file/temporary', 'temporary_destroy')->name('csv-destroy');
});