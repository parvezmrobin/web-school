<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('application_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('fathers_name')->nullable();
            $table->string('mothers_name')->nullable();
            $table->unsignedBigInteger('sex_id');
            $table->dateTime('birth_date');
            $table->string('address');
            $table->string('email')->nullable();
            $table->string('contact_no', 32);
            $table->text('info');
            $table->timestamps();

            $table->foreign('sex_id')->references('id')->on('sexes')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('application_id')->references('id')->on('applications')
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
        Schema::dropIfExists('responses');
    }
}
