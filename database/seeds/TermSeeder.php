<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $terms = ["First Term", "Second Term", "Final Term"];
        foreach ($terms as $key => $term) {
            DB::table('terms')->insert([
                'term' => $term,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $terms = DB::table('terms')->get();
        $YCSes = DB::table('class_section_year')->get();

        foreach ($terms as $termKey => $term) {
            foreach ($YCSes as $ycsKey => $CSY) {
                DB::table('class_section_year_term')->insert([
                    'term_id' => $term->id,
                    'class_section_year_id' => $CSY->id,
                    'percentage' => 33.3,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
