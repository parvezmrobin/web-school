<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use App\SubjectTeacher;

class SubjectTeacherController extends Controller
{
    private $table = 'subject_teacher';

    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user->isInRole(['admin', 'editor', 'teacher'])) {
            return response()->json(["status"=>"Unauthorized"], 403);
        }
        $conds = [];
        if ($user->isInRole('teacher')) {
            array_push($conds, ['teacher_id', $user->id]);
        }
        if ($request->input('teacher')) {
            array_push($conds, ['teacher_id', $request->input('teacher')]);
        }
        if ($request->input('subject')) {
            array_push($conds, ['subject_id', $request->input('subject')]);
        }
        if ($request->input('csy')){
            array_push($conds, ['class_section_year_id',$request->input('csy')]);
        }
        if ($request->input('cid')){
            array_push($conds, ['class_id',$request->input('cid')]);
        }
        if ($request->input('sid')){
            array_push($conds, ['section_id',$request->input('sid')]);
        }
        if ($request->input('yid')){
            array_push($conds, ['year_id',$request->input('yid')]);
        }

        $selects = [
            'subject_teacher.*',
            'subjects.subject_code',
            'subjects.subject',
            'subjects.mark',
            'subjects.is_compulsory',
            'users.first_name',
            'users.last_name',
            'class_id',
            'class',
            'section',
            'section_id',
            'year',
            'year_id',
        ];
        $res = DB::table($this->table)
        ->join('subjects', 'subject_id', 'subjects.id')
        ->join('class_section_year', 'class_section_year_id', 'class_section_year.id')
        ->join('classes', 'classes.id', 'class_id')
        ->join('sections', 'sections.id', 'section_id')
        ->join('years', 'years.id', 'year_id')
        ->join('users', 'teacher_id', 'users.id')
        ->select($selects)
        ->where($conds)->get();

        return response()->json($res);;
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if($user->isInRole('admin')){
            $st = SubjectTeacher::where([
                'subject_id' => $request->input('subject'),
                'teacher_id' => $request->input('teacher'),
                'class_section_year_id' =>  $request->input('csy')
            ]
            )->first();
            if (isset($st->id)) {
                return response()->json($st);
            }
            $st = new SubjectTeacher;
            $st->subject_id = $request->input('subject');
            $st->teacher_id = $request->input('teacher');
            $st->class_section_year_id = $request->input('csy');
            $st->save();

            return response()->json($st);
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
            $res = DB::table($this->table)->where('id', $id)->first();
            return response()->json($res);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function delete(Request $request, $id)
    {
        $user = $request->user();
        if($user->isInRole('admin')){
            DB::table($this->table)->where('id', $id)->delete();
            return response()->json(["status" => "succeeded"]);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }
}
