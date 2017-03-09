<?php

use Illuminate\Database\Seeder;

class StudentRollSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $students = DB::table('students')->get();
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

        foreach ($students as $key => $student) {
            if ($key < 3) {
                DB::table('student_roll')->insert([
                    'student_id' => $student->id,
                    'class_section_year_id' => $YCSId1->id,
                    'roll' => $key,
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                ]);
            }else {
                DB::table('student_roll')->insert([
                    'student_id' => $student->id,
                    'class_section_year_id' => $YCSId2->id,
                    'roll' => $key,
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                ]);
            }
        }
    }
}
