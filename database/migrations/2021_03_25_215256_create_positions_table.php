<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('classroom_id');

            
            $table->unsignedBigInteger('positionable_id');
            $table->string('positionable_type'); //App/Model/Subject

            $table->unsignedBigInteger('position');
            $table->unique(['classroom_id','positionable_id', 'positionable_type'],'classroom_position_index');
            
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
        Schema::dropIfExists('positions');
    }
}
