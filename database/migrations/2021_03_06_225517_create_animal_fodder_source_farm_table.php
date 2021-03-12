<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnimalFodderSourceFarmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animal_fodder_source_farm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_fodder_source_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('farm_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->timestamps();

            /* $table->foreign('animal_fodder_source_id')->references('id')->on('animal_fodder_sources')
                ->onDelete('cascade');
            $table->foreign('farm_id')->references('id')->on('farms')
                ->onDelete('cascade'); */

            $table->unique(['animal_fodder_source_id', 'farm_id'],'afoddersrc_farm_unique');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animal_fodder_source_farm');
    }
}
