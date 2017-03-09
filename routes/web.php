<?php

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/api/token', function ()
{
    $token = JWTAuth::fromUser(Auth::user());
    return response()->json(["token" => $token]);
});

Route::get('/home', 'HomeController@index');

Route::resource('post', 'PostController');
