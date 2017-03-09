<?php

use Illuminate\Database\Seeder;

class ClassSectionYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coll = collect(['One', 'Two', 'Three', 'Four', 'Five']);
        foreach ($coll as $key => $class) {
            DB::table('classes')->insert([
                'id' => $key +1,
                'class' => $class,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]);
        }

        $coll = collect(['A', 'B', 'C']);
        foreach ($coll as $key => $section) {
            DB::table('sections')->insert([
                'id' => $key + 1,
                'section' => $section,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]);
        }

        $coll = collect([2015, 2016, 2017]);
        foreach ($coll as $key => $year) {
            DB::table('years')->insert([
                'id' => $key + 1,
                'year' => $year,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]);
        }

        $years = \App\Year::all();
        $sections = \App\Section::all();
        $classes = \App\Classs::all();

        foreach ($years as $yearKey => $year) {
            foreach ($sections as $sectionKey => $section) {
                foreach ($classes as $classKey => $class) {
                    DB::table('class_section_year')->insert([
                        'class_id' => $class->id,
                        'section_id' => $section->id,
                        'year_id' => $year->id,
                        'created_at' => \Carbon\Carbon::now(),
                        'updated_at' => \Carbon\Carbon::now(),
                    ]);
                }
            }
        }
    }
}
