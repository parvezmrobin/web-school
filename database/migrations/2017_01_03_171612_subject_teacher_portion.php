<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SubjectTeacherPortion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_teacher_portion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('subject_teacher_id');
            $table->unsignedBigInteger('portion_id');
            $table->integer('percentage');
            $table->timestamps();

            $table->unique(['subject_teacher_id', 'portion_id'], 'unique_key');

            $table->foreign('subject_teacher_id')->references('id')->on('subject_teacher')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('portion_id')->references('id')->on('portions')
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
        Schema::dropIfExists('subject_teacher_portion');
    }
}
