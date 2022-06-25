<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth')->group(function () {

    Route::get('users', [UsersController::class, 'users']);
    Route::post('users', [UsersController::class, 'createUser']);
    Route::get('users/{id}', [UsersController::class, 'user']);
    Route::post('users/{id}', [UsersController::class, 'updateUser']);
    Route::get('users/toggle/{id}', [UsersController::class, 'toggle']);
    Route::get('users/delete/{id}', [UsersController::class, 'delete']);


    Route::get('/', [HomeController::class, 'home'])->name('home');
    Route::get('/logout', [HomeController::class, 'logout'])->name('logout');
    Route::get('/home', [HomeController::class, 'home']);

});



Route::post('/login', [HomeController::class, 'submitLogin']);
Route::get('/login', [HomeController::class, 'login'])->name('login');
