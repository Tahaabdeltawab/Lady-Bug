<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChemicalFertilizerSourceFarmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chemical_fertilizer_source_farm', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chemical_fertilizer_source_id');
            $table->unsignedBigInteger('farm_id');

            $table->foreign('chemical_fertilizer_source_id', 'chfsrc_chfarm_foreign')->references('id')->on('chemical_fertilizer_sources')
                ->onDelete('CASCADE');
            $table->foreign('farm_id','farm_chfarm_foreign')->references('id')->on('farms')
                ->onDelete('CASCADE');

            $table->unique(['chemical_fertilizer_source_id', 'farm_id'],'chfsrc_farm_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chemical_fertilizer_source_farm');
    }
}
