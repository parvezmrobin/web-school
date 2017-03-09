<?php

use Illuminate\Database\Seeder;

class SubjectTeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subjects = \App\Subject::all();
        $teachers = DB::table('teachers')->get();
        $YCSId1 = DB::table('class_section_year')
        ->where('class_id', 5)
        ->where('section_id', 1)
        ->where('year_id', 3)
        ->get()->first();
        $YCSId2 = DB::table('class_section_year')
        ->where('class_id', 5)
        ->where('section_id', 2)
        ->where('year_id', 3)
        ->get()->first();

        foreach ($subjects as $key => $subject) {
            DB::table('subject_teacher')->insert([
                'subject_id' => $subject->id,
                'teacher_id' => $teachers[0]->id,
                'class_section_year_id' => $YCSId1->id,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]);
        }
        foreach ($subjects as $key => $subject) {
            DB::table('subject_teacher')->insert([
                'subject_id' => $subject->id,
                'teacher_id' => $teachers[1]->id,
                'class_section_year_id' => $YCSId2->id,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]);
        }
    }
}
