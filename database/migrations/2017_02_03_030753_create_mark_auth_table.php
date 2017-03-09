<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarkAuthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mark_auth', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('subject_teacher_id');
            $table->unsignedBigInteger('editor_id');
            $table->timestamps();

            $table->foreign('subject_teacher_id')->references('id')->on('subject_teacher')
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
        Schema::dropIfExists('mark_auth');
    }
}
