<?php

use App\Http\Controllers\MaxController;

use App\Http\Controllers\UserController;

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

Route::post('/', function () {
    return view('welcome');
});


Route::get('/Max', 'MaxController@view')->name("max");
// Route::get('/Max/api/db', 'MaxController@dbRead')->middleware('token.check');
Route::get('/Max/api/insert', 'MaxController@fetch');
Route::get('/Max/store', 'MaxController@store');

Route::group(['middleware' => ['token.check']], function () {

    Route::get('/Max/api/db', 'MaxController@dbRead');
    Route::post('/Max/api/user/update', 'UserController@userUpdate');
    Route::post('/Max/api/user/logout', 'UserController@userLogout');
});

Route::get('/Max/login', 'UserController@login');
Route::post('/Max/api/user/create', 'UserController@userCreate');
// Route::post('/Max/api/user/update', 'UserController@userUpdate')->middleware('token.check');
Route::post('/Max/api/user/profile', 'UserController@userProfile');
Route::post('/Max/api/user/login', 'UserController@userLogin');
// Route::post('/Max/api/user/logout', 'UserController@userLogout')->middleware('token.check');

// Route::post('/Max/api/user/login', 'UserController@index');
