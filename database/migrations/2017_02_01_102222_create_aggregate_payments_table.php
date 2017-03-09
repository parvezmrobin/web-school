<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAggregatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aggregate_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('aggregate_impose_id');
            $table->unsignedBigInteger('student_roll_id');
            $table->unsignedBigInteger('receiver_id');
            $table->timestamps();


            $table->foreign('student_roll_id')
                ->references('id')->on('student_roll')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('aggregate_impose_id')
                ->references('id')->on('aggregate_imposes')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')
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
        Schema::dropIfExists('aggregate_payments');
    }
}
