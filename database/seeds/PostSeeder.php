<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class PostSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        $faker = Faker\Factory::create();
        $editors = App\Editor::all();
        for ($i=0; $i < 20; $i++) {
            DB::table('posts')->insert([
                'title' => $faker->sentence,
                'body' => $faker->paragraph(rand(10, 20)),
                'type' => collect([1, 2, 3])->random(),
                'is_open' => 1,
                'user_id' => $editors->random()->id,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]);

        }
    }
}
