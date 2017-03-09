<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ResponseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $apps = App\Application::all();
        $faker = Faker\Factory::create();

        foreach ($apps as $key => $app) {
            for ($i=0; $i < 10; $i++) {
                $resp = new App\Response;
                $resp->application_id = $app->id;
                $resp->first_name = $faker->firstName;
                $resp->last_name = $faker->lastName;
                $resp->fathers_name = $faker->name('male');
                $resp->mothers_name = $faker->name('female');
                $resp->sex_id = rand(1, 3);
                $resp->birth_date = $faker->dateTime(Carbon::now()->subYears(10));
                $resp->address = $faker->address;
                $resp->email = $faker->safeEmail;
                $resp->contact_no = $faker->e164PhoneNumber;
                if ($app->for_student) {
                    $resp->info = [
                        "guardian_occupation" => $faker->jobTitle,
                        "guardian_occupation_detail" =>  $faker->sentence
                    ];
                }else {
                    $resp->info = [
                        "qualification" => $faker->paragraph
                    ];
                }
                $resp->save();
            }
        }
    }
}
