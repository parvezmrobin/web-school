<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndividualTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individual_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_roll_id');
            $table->unsignedBigInteger('transaction_type_id');
            $table->string('detail');
            $table->bigInteger('ammount');
            $table->boolean('is_paid')->default(0);
            $table->unsignedBigInteger('imposer_id');
            $table->unsignedBigInteger('receiver_id')->nullable();
            $table->timestamps();

            $table->foreign('student_roll_id')
                ->references('id')->on('student_roll')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('transaction_type_id')->references('id')->on('transaction_types')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('imposer_id')->references('id')->on('users')
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
        Schema::dropIfExists('individual_transactions');
    }
}
