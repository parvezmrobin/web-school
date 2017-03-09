<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        $faker = Faker\Factory::create();

        for ($i=0; $i < 10; $i++) {
            $user = new \App\User;
            $user->sex_id = collect([1, 2, 3])->random();
            if($user->sex_id == 1){
                $user->first_name = $faker->firstNameFemale;
            } else if ($user->sex_id ==2) {
                $user->first_name = $faker->firstNameMale;
            } else {
                $user->first_name = $faker->firstName;
            }
            $user->last_name = $faker->lastName;
            $user->fathers_name = $faker->name('male');
            $user->mothers_name = $faker->name('female');
            $user->address = $faker->address;
            $user->email = $faker->unique()->safeEmail();
            $user->password = bcrypt('secret');
            $user->remember_token = str_random(20);
            $user->save();
            if ($i < 6) {
                DB::table('students')->insert([
                    'id' => $user->id,
                    'guardian_occupation' => $faker->sentence,
                    'guardian_occupation_detail' => $faker->paragraph,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

            }elseif ($i < 8) {
                DB::table('teachers')->insert([
                    'id' => $user->id,
                    'designation_id' => collect([1, 2])-> random(),
                    'qualification' => $faker->paragraph,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

            } elseif ($i < 9) {
                DB::table('editors')->insert([
                    'id' => $user->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }else {
                DB::table('admins')->insert([
                    'id' => $user->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
