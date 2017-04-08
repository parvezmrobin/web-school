<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('post/type/{type}', 'PostController@byType');
Route::get('post/{id}', 'PostController@byId');

Route::get('app', 'ApplicationController@index');
Route::post('app', 'ApplicationController@store');

Route::post('response', 'ResponseController@store');
Route::get('loggedUser', function ()
{
    return response()->json(Auth::user());
});

Route::group(['middleware' => 'jwt.auth'], function ()
{
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/roles', function (Request $request) {
        return $request->user()->roles;
    });

    Route::get('year', 'YearController@index');
    Route::post('year', 'YearController@store');
    Route::put('year/{id}', 'YearController@update');
    Route::delete('year/{id}', 'YearController@destroy');

    Route::get('class', 'ClassController@index');
    Route::post('class', 'ClassController@store');
    Route::put('class/{id}', 'ClassController@update');
    Route::delete('class/{id}', 'ClassController@destroy');

    Route::get('section', 'SectionController@index');
    Route::post('section', 'SectionController@store');
    Route::put('section/{id}', 'SectionController@update');
    Route::delete('section/{id}', 'SectionController@destroy');

    Route::put('app/{id}', 'ApplicationController@update');
    Route::delete('app/{id}', 'ApplicationController@delete');
});
