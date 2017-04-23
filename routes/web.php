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
Route::get('application', function ()
{
    return view('application/index');
});

Route::get('admin/subject-teacher', function ()
{
    return view('admin.subject-teacher');
});

Route::get('admin/auth', function ()
{
    return view('admin.auth');
});

Route::get('admin/term', function ()
{
    return view('admin.term');
});

Route::get('common/portion', function ()
{
  return view('common.subject-portion');
});

Route::get('student/mark', function ()
{
  return view('student.mark');
});

Route::get('common/tabulation', function ()
{
  return view('common.tabulation');
});

Route::get('common/mark/update', function ()
{
  return view('common.mark-update');
});
