<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SexSeeder::class);
        $this->call(DesignationSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PostSeeder::class);
        $this->call(ClassSectionYearSeeder::class);
        $this->call(StudentRollSeeder::class);
        $this->call(TermSeeder::class);
        $this->call(SubjectSeeder::class);
        $this->call(SubjectTeacherSeeder::class);
        $this->call(SubjectTeacherStudentSeeder::class);
        $this->call(PortionSeeder::class);
        $this->call(MarkSeeder::class);
        $this->call(ApplicationSeeder::class);
        $this->call(ResponseSeeder::class);
        $this->call(TransactionTypeSeeder::class);
        $this->call(TransactionSeeder::class);
        $this->call(PaymentSeeder::class);

    }
}
