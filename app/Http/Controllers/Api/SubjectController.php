<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Term;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(Subject::all());
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if($user->isInRole('admin')){
            $this->validate($request, [
                'subject_code' => 'bail|required|max:16',
                'subject' => 'bail|required|max:64',
                'mark' => 'bail|required|numeric',
                ]);

            $sub = new Subject;
            $sub->subject_code = $request->input('subject_code');
            $sub->subject = $request->input('subject');
            $sub->mark = $request->input('mark');
            $sub->save();

            return response()->json($sub);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        if($user->isInRole('admin')){
            $this->validate($request, [
                'subject_code' => 'bail|required|max:16',
                'subject' => 'bail|required|max:64',
                'mark' => 'bail|required|numeric',
                ]);

            $sub = Subject::find($id);
            $sub->subject_code = $request->input('subject_code');
            $sub->subject = $request->input('subject');
            $sub->mark = $request->input('mark');
            $sub->save();

            return response()->json($sub);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function delete(Request $request, $id)
    {
        $user = $request->user();
        if($user->isInRole('admin')){
            Subject::where('id', $id)->delete();
            return response()->json(["status" => "succeeded"]);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }
}
