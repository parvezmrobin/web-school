<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionAuthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_auth', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('class_section_year_id');
            $table->unsignedBigInteger('editor_id');
            $table->timestamps();

            $table->foreign('class_section_year_id')->references('id')->on('class_section_year')
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
        Schema::dropIfExists('transaction_auth');
    }
}
