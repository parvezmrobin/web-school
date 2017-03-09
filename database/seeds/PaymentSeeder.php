<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $csytrans = DB::table('aggregate_imposes')->get();

        for ($i=0; $i < $csytrans->count(); $i+=2) {
            $students = DB::table('student_roll')
                ->where('class_section_year_id', $csytrans->get($i)->class_section_year_id)
                ->get();
            foreach ($students as $key => $student) {
                DB::table('aggregate_payments')
                ->insert([
                    'aggregate_impose_id' => $csytrans->get($i)->id,
                    'student_roll_id' => $student->id,
                    'receiver_id' => 9,
                    'created_at' => new Carbon,
                    'updated_at' => new Carbon,
                ]);
            }
        }
    }
}
