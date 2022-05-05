<?php

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
Route::get('/Max/api/insert', 'MaxController@FetchContest');
// Route::get('/Max/store', 'MaxController@store');

Route::group(['middleware' => ['token.check']], function () {

    Route::get('/Max/api/db', 'MaxController@dbRead');
    Route::post('/Max/api/user/update', 'UserController@UserUpdate');
    Route::post('/Max/api/user/logout', 'UserController@UserLogout');
});


Route::get('/Max/login', function () {
    return view('login');
});

Route::group(['prefix' => '/Max/api/user'], function () {

    Route::post('create', 'UserController@UserCreate');
    Route::post('profile', 'UserController@UserProfile');
    Route::post('login', 'UserController@UserLogin');

});



