<?php

use Illuminate\Database\Seeder;

class MarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stdsubs = DB::table('subject_teacher_student')->get();

        foreach ($stdsubs as $sKey => $stdsub) {
            $portions = DB::table('subject_teacher_portion')
            ->where('subject_teacher_id', $stdsub->subject_teacher_id)
            ->get();
            $terms = DB::table('class_section_year_term')
            ->join('student_roll', 'class_section_year_term.class_section_year_id', 'student_roll.class_section_year_id')
            ->where('student_roll.id', $stdsub->student_roll_id)
            ->select('class_section_year_term.*')
            ->get();

            foreach ($portions as $pKey => $portion) {
                foreach ($terms as $tKey => $term) {                    
                    $max = $portion->percentage;
                    $mark = rand(-2, $max);
                    DB::table('marks')->insert([
                        'subject_teacher_portion_id' => $portion->id,
                        'subject_teacher_student_id' => $stdsub->id,
                        'class_section_year_term_id' => $term->id,
                        'mark' => $mark,
                        'editor_id' => 9,
                        'created_at' => Carbon\Carbon::now(),
                        'updated_at' => Carbon\Carbon::now(),
                    ]);
                }
            }
        }
    }
}
