<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TermClassSectionYear extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_section_year_term', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('term_id');
            $table->unsignedBigInteger('class_section_year_id');
            $table->integer('percentage');
            $table->timestamps();

            $table->unique(['term_id', 'class_section_year_id'], 'unique_key');

            $table->foreign('term_id')->references('id')->on('terms')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('class_section_year_id')
                ->references('id')->on('class_section_year')
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
        Schema::dropIfExists('class_section_year_term');
    }
}
