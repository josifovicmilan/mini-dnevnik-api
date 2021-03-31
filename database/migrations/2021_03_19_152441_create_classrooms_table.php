<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassroomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classrooms', function (Blueprint $table) {
            $table->id();
            $table->integer('year_started');
            $table->smallInteger('classroom_number');
            $table->smallInteger('duration')->default(4);
            $table->string('type');
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->unique(['year_started','classroom_number','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classrooms');
    }
}
