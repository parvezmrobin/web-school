<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Mark;
use DB;

class ClassSectionYearTermController extends Controller
{
    private $table = "class_section_year_term";

    public function index(Request $request)
    {
        $conds = [];

        if ($request->input('csy')) {
            $conds['class_section_year_id'] = $request->input('csy');
        }
        if ($request->input('term')) {
            $cond['term_id'] = $request->input('term');
        }

        $res = DB::table($this->table)
        ->join('class_section_year', 'class_section_year_id', 'class_section_year.id')
        ->join('classes', 'class_id', 'classes.id')
        ->join('sections', 'section_id', 'sections.id')
        ->join('years', 'year_id', 'years.id')
        ->join('terms', 'term_id', 'terms.id')
        ->select('class_id', 'class', 'section_id', 'section', 'year_id', 'year', 'term_id', 'term', 'percentage', 'class_section_year_term.id')
        ->where($conds)->orderByRaw('year_id, class_id, section_id, term_id')->get();
        return response()->json($res);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if($user->isInRole(['admin'])){
            // If already exists, return error
            $res = DB::table($this->table)
            ->where('class_section_year_id', $request->input('csy'))
            ->where('term_id', $request->input('term'))
            ->first();
            if (isset($res->id)) {
                return response()->json(["status" => "Already exists"], 500);
            }

            // else create a new entry
            $vals = [
                'class_section_year_id' => $request->input('csy'),
                'term_id' => $request->input('term'),
                'percentage' => $request->input('percentage'),
                'created_at' => new Carbon,
                'updated_at' => new Carbon,
            ];
            $id = DB::table($this->table)->insertGetId($vals);
            $res = DB::table($this->table)
            ->where('id', $id)
            ->first();


            // Get the subjectTeacherStudents and SubjectTeacherPortions
            $subjectTeacherStudentPortions = DB::table('subject_teacher_student')
                ->join('subject_teacher', 'subject_teacher.id', 'subject_teacher_student.subject_teacher_id')
                ->join('subject_teacher_portion', 'subject_teacher_portion.subject_teacher_id', 'subject_teacher.id')
                ->where('subject_teacher.class_section_year_id', $request->input('csy'))
                ->select(
                    'subject_teacher_student.id as subject_teacher_student_id',
                    'subject_teacher_portion.id as subject_teacher_portion_id'
                )->distinct()
                ->get();

            $count = 0;

                foreach ($subjectTeacherStudentPortions as $subjectTeacherStudentPortion) {
                    $mark = new Mark;
                    $mark->subject_teacher_portion_id =
                        $subjectTeacherStudentPortion->subject_teacher_portion_id;
                    $mark->subject_teacher_student_id =
                        $subjectTeacherStudentPortion->subject_teacher_student_id;
                    $mark->class_section_year_term_id = $id;
                    $mark->mark = -2;
                    $mark->editor_id = $user->id;
                    $mark->created_at = new Carbon();
                    $mark->updated_at = new Carbon();
                    $mark->save();
                    $count++;
                }

            $res->count = $count;

            return response()->json($res);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        if($user->isInRole('admin')){
            $conds = [];

            if ($request->input('csy')) {
                $conds['class_section_year_id'] = $request->input('csy');
            }
            if ($request->input('term')) {
                $cond['term_id'] = $request->input('term');
            }

            DB::table($this->table)->where('id', $id)->update($conds);
            $res = DB::table($this->table)
            ->join('classes', 'class_id', 'classes.id')
            ->join('sections', 'section_id', 'sections.id')
            ->join('years', 'year_id', 'years.id')
            ->join('terms', 'term_id', 'terms.id')
            ->select('class_id', 'class', 'section_id', 'section', 'year_id', 'year', 'term_id', 'term', 'class_section_year_term.id')
            ->where('id', $id)->orderByRaw('year_id, class_id, section_id, term_id')->first();
            return response()->json($res);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }

    public function delete(Request $request)
    {
        $user = $request->user();
        if($user->isInRole(['admin'])){
            DB::table($this->table)->where('id', $request->input('id'))->delete();
            return response()->json(["status" => "succeeded"]);
        }
        return response()->json(["status"=>"Unauthorized"], 403);
    }
}
