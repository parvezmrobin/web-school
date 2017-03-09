<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class SubjectTeacherPortionController extends Controller
{
    private $table = 'subject_teacher_portion';

    public function index(Request $request)
    {
        $conds = [];
        if($request->input('st')){
            $conds['subject_teacher_id'] = $request->input('st');
        }
        if($request->input('portion')){
            $conds['portion_id'] = $request->input('portion');
        }

        $res = DB::table($table)->where($conds)->get();
        return response()->json($res);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $subTeaId = $request->input('st');
        $teacher = DB::table('subject_teacher')
            ->join('teachers', 'subject_teacher.teacher_id', 'teachers.id')
            ->join('users', 'users.id', 'teachers.user_id')
            ->where('subject_teacher.id', $subTeaId)->first();

        if($user->isInRole('admin') || $teacher->id === $user->id){
            $vals = [
                'subject_teacher_id' => $subTeaId,
                'portion_id' => $request->input('portion'),
                'percentage' => $request->input('percentage'),
            ];
            if ( ! is_null($instance = DB::table('class_section_year')->where($vals)->first())){
                return $instance;
            }
            $vals = array_merge($vals, ['created_at' => new Carbon, 'updated_at' => new Carbon]);
            $id = DB::table($table)->insertGetId($vals);
            $stp = DB::table($table)->where('id', $id)->first();

            return response()->json($stp);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function update(Request $request, $id)
    {
        $subTeaId = $request->input('st');
        $teacher = DB::table($table)
            ->join('subject_teacher', 'subject_teacher.id', 'subject_teacher_portion.subject_teacher_id')
            ->join('teachers', 'subject_teacher.teacher_id', 'teachers.id')
            ->select('teachers.*')
            ->where('subject_teacher.id', $subTeaId)->first();
        $user = $request->user();
        if($user->isInRole('admin') || $user->id === $teacher->user_id){
            $vals = [
                'subject_teacher_id' => $subTeaId,
                'portion_id' => $request->input('portion'),
                'percentage' => $request->input('percentage'),
            ];

            DB::table($table)->where('id', $id)->update($vals);
            $res = DB::table($table)->where('id', $id)->first();

            return response()->json($res);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function delete(Request $request, $id)
    {
        $subTeaId = $request->input('st');
        $teacher = DB::table($table)
            ->join('subject_teacher', 'subject_teacher.id', 'subject_teacher_portion.subject_teacher_id')
            ->join('teachers', 'subject_teacher.teacher_id', 'teachers.id')
            ->select('teachers.*')
            ->where('subject_teacher.id', $subTeaId)->first();
        $user = $request->user();
        if($user->isInRole('admin') || $user->id === $teacher->user_id){
            DB::table($table)->where('id', $id)->delete();
            return response()->json(["status" => "succeeded"]);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }
}
