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

  Route::get('csy', 'ClassSectionYearController@index');
  Route::post('csy', 'ClassSectionYearController@store');
  Route::put('csy/{id}', 'ClassSectionYearController@update');
  Route::delete('csy/{id}', 'ClassSectionYearController@delete');

  Route::get('csyt', 'ClassSectionYearTermController@index');
  Route::post('csyt', 'ClassSectionYearTermController@store');
  Route::put('csyt/{id}', 'ClassSectionYearTermController@update');
  Route::post('csyt/delete', 'ClassSectionYearTermController@delete');

  Route::get('portion', 'PortionController@index');
  Route::post('portion', 'PortionController@store');
  Route::put('portion/{id}', 'PortionController@update');
  Route::delete('portion/{id}', 'PortionController@destroy');

  Route::get('post/type/{type}', 'PostController@byType');
  Route::get('post/{id}', 'PostController@byId');
  Route::post('post', 'PostController@store');
  Route::put('post/{id}', 'PostController@update');
  Route::delete('post/{id}', 'PostController@delete');

  Route::get('response', 'ResponseController@index');
  Route::post('response', 'ResponseController@store');
  Route::delete('response/{id}', 'ResponseController@destroy');

  Route::get('subject', 'SubjectController@index');
  Route::post('subject', 'SubjectController@subjectore');
  Route::put('subject/{id}', 'SubjectController@update');
  Route::delete('subject/{id}', 'SubjectController@desubjectroy');

  Route::get('st', 'SubjectTeacherController@index');
  Route::post('st', 'SubjectTeacherController@store');
  Route::put('st/{id}', 'SubjectTeacherController@update');
  Route::get('st/delete/{id}', 'SubjectTeacherController@delete');

  Route::get('stp', 'SubjectTeacherPortionController@index');
  Route::get('stp/store', 'SubjectTeacherPortionController@store')->name('stp.store');
  Route::put('stp/{id}', 'SubjectTeacherPortionController@update');
  Route::get('stp/delete/{id}', 'SubjectTeacherPortionController@delete');

  Route::get('term', 'TermController@index');
  Route::post('term', 'TermController@termore');
  Route::put('term/{id}', 'TermController@update');
  Route::delete('term/{id}', 'TermController@destsroy');

  Route::get('sr', 'StudentRollController@index');
  Route::post('sr', 'StudentRollController@srore');
  Route::put('sr/{id}', 'StudentRollController@update');
  Route::delete('sr/{id}', 'StudentRollController@destroy');

  Route::get('sts', 'SubjectTeacherStudentController@index');
  Route::post('sts', 'SubjectTeacherStudentController@stsore');
  Route::put('sts/{id}', 'SubjectTeacherStudentController@update');
  Route::delete('sts/{id}', 'SubjectTeacherStudentController@destsroy');

  Route::get('app', 'ApplicationController@index');
  Route::post('app', 'ApplicationController@store');
  Route::put('app/{id}', 'ApplicationController@update');
  Route::delete('app/{id}', 'ApplicationController@delete');

  Route::get('mark/update/{id}', 'MarkController@update');
  Route::get('mark', 'MarkController@index');
  Route::get('tabulation', 'MarkController@tabulation');

  Route::get('trans', 'TransactionController@index');
  Route::get('trans/recent', 'TransactionController@recentAggregateImposes');

  Route::get('user', 'UserController@index');

  Route::get('auth/mark', 'AuthController@markIndex');
  Route::get('auth/trans', 'AuthController@transIndex');
  Route::get('auth/mark/store', 'AuthController@markAuthStore');
  Route::get('auth/mark/delete/{id}', 'AuthController@markAuthRemove');
});
