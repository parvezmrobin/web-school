<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Term;

class TermController extends Controller
{
    public function index(Request $request)
    {
        $term = Term::all();
        return response()->json($term);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if ($user->isInRole(['admin'])) {
            $term = new Term;
            $term->term = $request->input('term');
            $term->save();
            dd($term);
            return response()->json($term);
        }
        return response()->json(["status" => "Unauthorized"], 403);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        if ($user->isInRole('admin')) {
            $this->validate($request, [
                'term' => 'bail|required|max:16',

            ]);
            $term = Term::find($id);
            if ($request->input('term')) {
                $term->term = $request->input('term');
            }
            $term->save();
            return response()->json($term);
        }
        return response()->json(["status" => "Unauthorized"], 403);
    }

    public function delete(Request $request, $id)
    {
        $user = $request->user();
        if ($user->isInRole('admin')) {
            Term::where('id', $id)->delete();
            return response()->json(["status" => "succeeded"]);
        }
        return response()->json(["status" => "Unauthorized"], 403);
    }
}
