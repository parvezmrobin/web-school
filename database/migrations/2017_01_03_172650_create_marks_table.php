<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('subject_teacher_portion_id');
            $table->unsignedBigInteger('subject_teacher_student_id');
            $table->unsignedBigInteger('class_section_year_term_id');
            $table->integer('mark');
            $table->unsignedBigInteger('editor_id');
            $table->timestamps();

            $table->unique([
                'subject_teacher_portion_id',
                'subject_teacher_student_id',
                'class_section_year_term_id'
            ], 'unique_key');

            $table->foreign('subject_teacher_portion_id')
                ->references('id')->on('subject_teacher_portion')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('subject_teacher_student_id')
                ->references('id')->on('subject_teacher_student')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('class_section_year_term_id')
                ->references('id')->on('class_section_year_term')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('editor_id')->references('id')->on('editors')
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
        Schema::dropIfExists('marks');
    }
}
