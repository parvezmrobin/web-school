<?php

use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subject = new \App\Subject;
        $subject->subject_code = "BAN";
        $subject->subject = "Bangla";
        $subject->mark = 100;
        $subject->save();

        $subject = new \App\Subject;
        $subject->subject_code = "ENG";
        $subject->subject = "English";
        $subject->mark = 100;
        $subject->save();

        $subject = new \App\Subject;
        $subject->subject_code = "MATH";
        $subject->subject = "Mathematics";
        $subject->mark = 100;
        $subject->save();

        $subject = new \App\Subject;
        $subject->subject_code = "SCI";
        $subject->subject = "Science";
        $subject->mark = 50;
        $subject->save();
    }
}
