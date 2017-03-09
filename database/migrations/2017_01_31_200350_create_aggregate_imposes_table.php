<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAggregateImposesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aggregate_imposes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('class_section_year_id');
            $table->unsignedBigInteger('transaction_type_id');
            $table->string('detail');
            $table->bigInteger('ammount');
            $table->unsignedBigInteger('imposer_id');
            $table->timestamps();

            $table->foreign('class_section_year_id')->references('id')->on('class_section_year')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('transaction_type_id')->references('id')->on('transaction_types')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('imposer_id')->references('id')->on('users')
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
        Schema::dropIfExists('aggregate_imposes');
    }
}
