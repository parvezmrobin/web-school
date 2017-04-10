<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Mark;

class MarkController extends Controller
{
    public function index(Request $request)
    {
        $conds = [];
        if ($request->input('stid')) {
            $conds = array_push($conds, ['subject_teacher_id', $request->input('stid')]);
        }
        if ($request->input('csyid')) {
            $conds = array_push($conds, ['class_section_year_id', $request->input('csyid')]);
        }
        if ($request->input('csytid')) {
            $conds = array_push($conds, ['class_section_year_term_id', $request->input('csytid')]);
        }
        if ($request->input('srid')) {
            $conds = array_push($conds, ['student_roll_id', $request->input('srid')]);
        }

        $mark = Mark::join('subject_teacher_student')
        ->join('class_section_year_term', 'class_section_year_term_id', 'class_section_year_term.id')
        ->join('subject_teacher_portion', 'subject_teacher_portion_id', 'subject_teacher_portion.id')
        ->join('subject_teacher_student', 'subject_teacher_student_id', 'subject_teacher_student.id')
        ->where($conds)
        ->select('marks.*')
        ->first();

        return response()->json($mark);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        $mark = Mark::join('subject_teacher_portion', 'subject_teacher_portion_id', 'subject_teacher_portion.id')
        ->where('id', $id)
        ->select('mark.*', 'subject_teacher_id')
        ->first();
        $auth = DB::table('mark_auth')
        ->where('subject_teacher_id', $mark->subject_teacher_id)
        ->where('editor_id', $user->id)
        ->get();
        if($user->isInRole(['admin', 'editor']) || $auth->count() > 0){
            if($request->input('mark')){$mark->mark = $request->input('mark');}
            $mark->save();
            return response()->json($mark);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }
}
