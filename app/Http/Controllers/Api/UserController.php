<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
  public function index(Request $request)
  {
    if($request->user()->isInRole(['admin'])){
      $role = $request->input('role');
      if (! strcmp($role, 'student')) {
        $users = User::join('teachers', 'users.id', 'students.id')
        ->select('users.*')
        ->get();
      }
      else if (! strcmp($role, 'teacher')) {
        $users = User::join('teachers', 'users.id', 'teachers.id')
        ->select('users.*')
        ->get();
      }
      else if (! strcmp($role, 'editor')) {
        $users = User::join('editors', 'users.id', 'editors.id')
        ->select('users.*')
        ->get();
      }
      else if (! strcmp($role, 'admin')) {
        $users = User::join('admins', 'users.id', 'admins.id')
        ->select('users.*')
        ->get();
      }
      else {
        $users = [];
      }
      return response()->json($users);
    }

    return response()->json(["status"=>"Unauthorized"], 403);
  }
}
