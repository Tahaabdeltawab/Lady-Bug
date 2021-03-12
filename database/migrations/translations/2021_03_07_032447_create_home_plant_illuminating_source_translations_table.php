<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomePlantIlluminatingSourceTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_plant_illuminating_source_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->unsignedBigInteger('home_plant_illuminating_source_id');
            $table->unique(['home_plant_illuminating_source_id', 'locale'],'hpisrctrans_hpisrcid_unique');
            $table->string('name');
            $table->foreign('home_plant_illuminating_source_id', 'hpisrctrans_hpisrcid_foreign')->references('id')->on('home_plant_illuminating_sources')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('home_plant_illuminating_source_translations');
    }
}
