<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        $faker = Faker\Factory::create();
        $csy = DB::table('class_section_year')->get();
        $types = DB::table('transaction_types')->get();

        foreach ($types as $tKey => $type) {

            foreach ($csy as $key => $value) {

                if($type->is_individual){
                    $students = DB::table('student_roll')
                    ->where('class_section_year_id', $value->id)->get();

                    foreach ($students as $key => $student) {
                        $paid = rand(0, 1);
                        DB::table('individual_transactions')->insert([
                            'student_roll_id' => $student->id,
                            'transaction_type_id' => $type->id,
                            'ammount' => rand(100, 1000),
                            'imposer_id' => 9,
                            'detail' => $faker->sentence,
                            'is_paid' => $paid,
                            'receiver_id' => ($paid) ? 9 : null,
                            'created_at' => new Carbon,
                            'updated_at' => new Carbon,
                        ]);
                    }

                }else {
                    DB::table('aggregate_imposes')->insert([
                        'class_section_year_id' => $value->id,
                        'transaction_type_id' => $type->id,
                        'ammount' => rand(100, 1000),
                        'imposer_id' => 9,
                        'detail' => $faker->sentence,
                        'created_at' => new Carbon,
                        'updated_at' => new Carbon,
                    ]);
                }
            }
        }
    }
}
