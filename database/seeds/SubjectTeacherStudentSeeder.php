<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SubjectTeacherStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csys = DB::table('class_section_year')->get();
        foreach ($csys as $csyKey => $csy) {
            $students = DB::table('student_roll')
                ->where('class_section_year_id', $csy->id)->get();
            $subjects = DB::table('subject_teacher')
                ->join('subjects', 'subject_teacher.subject_id', 'subjects.id')
                ->select(['subjects.is_compulsory', 'subject_teacher.id'])
                ->where('class_section_year_id', $csy->id)->get();
            foreach ($students as $stdKey => $student) {
                foreach ($subjects as $subKey => $subject) {
                    DB::table('subject_teacher_student')->insert([
                        'subject_teacher_id' => $subject->id,
                        'student_roll_id' => $student->id,
                        'is_compulsory' => ($subject->is_compulsory) ? 1 : rand(1),
                        'created_at' => new Carbon,
                        'updated_at' => new Carbon,
                    ]);
                }
            }
        }
    }
}
