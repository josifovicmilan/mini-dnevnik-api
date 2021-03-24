<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrimarySchoolDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('primary_school_data', function (Blueprint $table) {
            $table->id();
            $table->string('primary_school_name');
            $table->string('gender', 1);
            $table->unsignedBigInteger('language_subject');
            $table->unsignedBigInteger('chosen_subject');
            $table->unsignedBigInteger('packet_subject1');
            $table->unsignedBigInteger('packet_subject2');
            $table->double('points',3, 2);


            $table->foreign('language_subject')->references('id')->on('subjects');
            $table->foreign('chosen_subject')->references('id')->on('subjects');
            $table->foreign('packet_subject1')->references('id')->on('subjects');
            $table->foreign('packet_subject2')->references('id')->on('subjects');

            $table->foreignId('student_id')->constreined()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('primary_school_data');
    }
}
