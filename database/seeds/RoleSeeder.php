<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coll = collect(['Student', 'Teacher', 'Editor', 'Admin']);
        foreach ($coll as $key => $role) {
            DB::table('roles')->insert([
                'id' => $key + 1,
                'role' => $role,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
