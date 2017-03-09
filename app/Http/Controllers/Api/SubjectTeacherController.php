<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

class SubjectTeacherController extends Controller
{
    private $table = 'subject_teacher';

    public function index(Request $request)
    {
        $conds = [];

        if ($request->input('teacher')) {
            $conds['teacher_id'] = $request->input('teacher');
        }
        if ($request->input('subject')) {
            $conds['subject_id'] = $request->input('subject');
        }
        if($request->input('csy')){
            $conds['class_section_year_id'] = $request->input('csy');
        }

        $res = DB::table($table)->where($conds)->get();

        return response()->json($res);;
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if($user->isInRole('admin')){
            $id = DB::table($table)->insertGetId([
                'teacher_id' => $request->input('teacher'),
                'subject_id' => $request->input('subject'),
                'class_section_year_id' => $request->input('csy'),
                'created_at' => new Carbon,
                'updated_at' => new Carbon,
            ]);
            $res = DB::table($table)->where('id', $id)->first();
            return response()->json($res);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        if($user->isInRole('admin')){
            $conds = [];

            if ($request->input('teacher')) {
                $conds['teacher_id'] = $request->input('teacher');
            }
            if ($request->input('subject')) {
                $conds['subject_id'] = $request->input('subject');
            }
            if($request->input('csy')){
                $conds['class_section_year_id'] = $request->input('csy');
            }

            DB::table($table)->where('id', $id)->update($conds);
            $res = DB::table($table)->where('id', $id)->first();
            return response()->json($res);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function delete(Request $request, $id)
    {
        $user = $request->user();
        if($user->isInRole('admin')){
            DB::table($table)->where('id', $id)->delete();
            return response()->json(["status" => "succeeded"]);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }
}
