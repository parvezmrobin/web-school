<?php

use Illuminate\Database\Seeder;

class DesignationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (collect(['Assistatn Teacher', 'Senior Teacher']) as $key => $designation) {
            DB::table('designations')->insert([
                'id' => $key + 1,
                'designation' => $designation,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]);
        }
    }
}
