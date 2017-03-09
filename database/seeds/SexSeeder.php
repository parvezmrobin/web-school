<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SexSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sexes')->insert([
            'id' => 1,
            'sex' => 'Female',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('sexes')->insert([
            'id' => 2,
            'sex' => 'Male',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('sexes')->insert([
            'id' => 3,
            'sex' => 'Other',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
