<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = App\User::find(9)->id;
        $faker = Faker\Factory::create();
        $classes = DB::table('class_section_year')
            ->join('years', 'years.id', 'class_section_year.year_id')
            ->where('years.year', Carbon::now()->year)
            ->get();

        for ($i=0; $i < 5; $i++) {
            $app = new App\Application;
            $app->user_id = $user;
            $app->title = $faker->sentence;
            $app->detail = $faker->paragraph(5);
            $app->for_student = rand(0, 1);
            if($app->for_student){
                $app->info = '{"class" = "' . $classes->random()->class_id .'" }';
            }else {
                $app->info = '{"designation" = "' . rand(1,2) . '"}';
            }
            $app->deadline = Carbon::now()->addYears(1);
            $app->save();
        }
    }
}
