<?php

use Illuminate\Database\Seeder;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = ['Admission' => 1, 'Tution Fee' => 0, 'Exam Fee' => 0, 'Fine' => 1];
        foreach ($types as $key => $value) {
            $trans_type = new App\TransactionType;
            $trans_type->type = $key;
            $trans_type->is_individual = $value;
            $trans_type->min_diff = 20;
            $trans_type->save();
        }
    }
}
