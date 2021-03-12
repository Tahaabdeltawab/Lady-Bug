<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnimalMedicineSourceFarmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animal_medicine_source_farm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_medicine_source_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('farm_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->timestamps();

            /* $table->foreign('animal_medicine_source_id')->references('id')->on('animal_medicine_sources')
                ->onDelete('cascade');
            $table->foreign('farm_id')->references('id')->on('farms')
                ->onDelete('cascade'); */

            $table->unique(['animal_medicine_source_id', 'farm_id'],'amdcnsrc_farm_unique');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animal_medicine_source_farm');
    }
}
