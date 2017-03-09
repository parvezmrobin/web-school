<?php

use Illuminate\Database\Seeder;

class PortionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coll = ["Attendance", "Class Test", "Monthly Test", "Term Test"];
        foreach ($coll as $key => $portion) {
            DB::table('portions')->insert([
                'portion' =>  $portion,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]);
        }

        $marks = collect([20, 30, 40]);
        $subjects = DB::table('subject_teacher')->get();
        $portions = DB::table('portions')->get();

        foreach ($subjects as $subjectKey => $subject) {
            foreach ($portions as $key => $portion) {
                DB::table('subject_teacher_portion')->insert([
                    'subject_teacher_id' => $subject->id,
                    'portion_id' => $portion->id,
                    'percentage' => $marks->random(),
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                ]);
            }
        }
    }
}
