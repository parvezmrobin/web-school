<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Portion;

class PortionController extends Controller
{
  public function index(Request $request)
  {
    $user = $request->user();
    if (!$user->isInRole(['admin', 'editor', 'teacher'])) {
      return response()->json(["status"=>"Unauthorized"], 403);
    }
    $portions = Portion::all();
    return response()->json($portions);
  }

  public function store(Request $request)
  {
    $user = $request->user();
    if($user->isInRole('admin')){
      $this->validate($request, [
        'portion' => 'bail|required|max:32',
      ]);

      $portion = new Portion;
      $portion->portion = $request->input('portion');
      $portion->save();

      return response()->json($portion);
    }
    return response()->json(["status"=>"Unauthorized"], 403);
  }

  public function update(Request $request, $id)
  {
    $user = $request->user();
    if($user->isInRole('admin')){
      $this->validate($request, [
        'portion' => 'bail|required|max:32',
      ]);

      $portion = Portion::find($id);
      if($request->input('portion')){$portion->portion = $request->input('portion');}
      $portion->save();

      return response()->json($portion);
    }
    return response()->json(["status"=>"Unauthorized"], 403);
  }

  public function delete(Request $request, $id)
  {
    $user = $request->user();
    if($user->isInRole('admin')){
      Portion::where('id', $id)->delete();
      return response()->json(["status" => "succeeded"]);
    }
    return response()->json(["status"=>"Unauthorized"], 403);
  }
}
