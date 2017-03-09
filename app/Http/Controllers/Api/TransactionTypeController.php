<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\TransactionType;
use App\Http\Controllers\Controller;

class TransactionTypeController extends Controller
{
    public function index(Request $request)
    {
        $types = TransactionType::all();
        return response()->json($types);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if($user->isInRole(['admin', 'editor'])){
            $type = new TransactionType;
            $type->type = $request->input('type');
            $type->is_individual = $request->input('is_individual');
            $type->min_diff = $request->input('min_diff');
            $type->save();

            return response()->json($type);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        if($user->isInRole(['admin', 'editor'])){
            $type = TransactionType::find($id);
            if($request->input('type')){
                $type->type = $request->input('type');
            }
            if($request->input('is_individual')){
                $type->is_individual = $request->input('is_individual');
            }
            if($request->input('min_diff')){
                $type->min_diff = $request->input('min_diff');
            }

            $type->save();
            return response()->json($type);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function delete(Request $request, $id)
    {
        $user = $request->user();
        if($user->isInRole(['admin', 'editor'])){
            TransactionType::where('id', $id)->delete();
            return response()->json(["status" => "succeeded"]);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }
}
