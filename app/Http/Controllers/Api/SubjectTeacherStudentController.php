<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

class SubjectTeacherStudentController extends Controller
{
    private $table = 'subject_teacher_student';

    public function index(Request $request)
    {
        $user = $request->user();
        $conds = [];
        if ($user->isInRole(['admin', 'editor', 'teacher'])) {
            if ($request->input('srid')) {
                array_push($conds, ['student_roll_id', $request->input('srid')]);
            }
        } elseif ($user->isInRole('student')) {
            array_push($conds, ['student_id', $user->id]);
        } else {
            return response()->json(["status" => "Unauthorized"], 403);
        }

        if ($request->input('stid')) {
            array_push($conds, ['subject_teacher_id', $request->input('stid')]);
        }

        $res = DB::table($this->table)->where($conds)->get();
        return response()->json($res);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $stid = $request->input('stid');
        $subTeacher = DB::table('subject_teacher')->where('id', $stid)->first();

        if ($user->isInRole(['admin', 'editor']) || $user->id === $subTeacher->teacher_id) {
            try{
                $id = DB::table($this->table)->insertGetId([
                    'subject_teacher_id' => $stid,
                    'student_roll_id' => $request->input('srid'),
                    'is_compulsory' => $request->input('comp'),
                    'created_at' => new Carbon,
                    'updated_at' => new Carbon,
                ]);
            } catch (\Exception $e) {
                return response()->json(["status" => "Already Exsist"], 500);
            }

            $subject_teacher_portions = DB::table('subject_teacher_portion')
                ->where('subject_teacher_id', $stid)
                ->get();

            $class_section_year_terms = DB::table('student_roll')
                ->join(
                    'class_section_year_term',
                    'class_section_year_term.class_section_year_id',
                    'student_roll.class_section_year_id'
                )->where('id', $request->input('srid'))
                ->select('class_section_year_term.*')
                ->get();

            $inserts = [];

            foreach ($subject_teacher_portions as $subject_teacher_portion) {
                if (!empty($class_section_year_term)) {
                    foreach ($class_section_year_terms as $class_section_year_term) {
                        array_push($inserts, [
                            'subject_teacher_portion_id' => $subject_teacher_portion->id,
                            'subject_teacher_student_id' => $id,
                            'class_section_year_term_id' => $class_section_year_term->id,
                            'mark' => -2,
                            'editor_id' => $user->id,
                            'created_at' => new Carbon,
                            'updated_at' => new Carbon,
                        ]);
                    }
                }
            }

            DB::table('marks')->insert($inserts);

            return response()->json(DB::table($this->table)->where('id', $id)->first());
        }
        return response()->json(["status" => "Unauthorized"], 403);
    }

    public function delete(Request $request, $id)
    {
        $user = $request->user();
        $stid = $request->input('stid');
        $teacher = DB::table(subject_teacher)->where('id', $stid)->first();
        if ($user->isInRole(['admin', 'editor']) || $user->id === $teacher->id) {
            DB::table($this->table)->where('id', $id)->delete();
            return response()->json(['status' => 'succeeded']);
        }
        return response()->json(["status" => "Unauthorized"], 403);
    }
}
