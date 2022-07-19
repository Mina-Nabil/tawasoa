<?php

use App\Http\Controllers\EntriesController;
use App\Http\Controllers\EquationsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VariablesController;
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

//entries route group
Route::middleware('auth')->group(function () {

    Route::post('search', [EntriesController::class, 'search']);
    Route::post('entry', [EntriesController::class, 'submitEntry']);
    Route::get('entry/{id}', [EntriesController::class, 'entry']);
    Route::get('entries/history', [EntriesController::class, 'entries']);
    Route::get('entries/main', [EntriesController::class, 'summary']);
    Route::get('rawdata/history', [EntriesController::class, 'rawdata']);
    Route::get('rawdata/main', [EntriesController::class, 'mainRawdata']);
    Route::get('entry/delete/{id}', [EntriesController::class, 'delete']);
    Route::get('entry/main/{id}', [EntriesController::class, 'setAsMain']);
    Route::get('entry/notmain/{id}', [EntriesController::class, 'setAsNotMain']);

});

//equations route group
Route::middleware('auth')->group(function () {

    Route::get('equations', [EquationsController::class, 'equations']);
    Route::post('equations', [EquationsController::class, 'addEquation']);
    Route::get('equations/{id}', [EquationsController::class, 'equation']);
    Route::post('equations/{id}', [EquationsController::class, 'updateEquation']);
    Route::get('equations/delete/{id}', [EquationsController::class, 'delete']);

});

//variables route group
Route::middleware('auth')->group(function () {

    Route::get('variables', [VariablesController::class, 'variables']);
    Route::post('variables', [VariablesController::class, 'addVariable']);
    Route::get('variables/{id}', [VariablesController::class, 'variable']);
    Route::post('variables/{id}', [VariablesController::class, 'updateVariable']);
    Route::get('variables/delete/{id}', [VariablesController::class, 'delete']);

});

//users & auth route group
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
