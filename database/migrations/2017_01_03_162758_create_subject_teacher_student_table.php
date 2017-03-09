<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectTeacherStudentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_teacher_student', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('subject_teacher_id');
            $table->unsignedBigInteger('student_roll_id');
            $table->boolean('is_compulsory')->default(1);
            $table->timestamps();

            $table->unique(['subject_teacher_id', 'student_roll_id'], 'unique_key');

            $table->foreign('subject_teacher_id')->references('id')->on('subject_teacher')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('student_roll_id')->references('id')->on('student_roll')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subject_teacher_student');
    }
}
