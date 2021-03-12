<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnimalMedicineSourceTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animal_medicine_source_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->unsignedBigInteger('animal_medicine_source_id');
            $table->unique(['animal_medicine_source_id', 'locale'],'amsrctrans_amsrcid_unique');
            $table->string('name');
            $table->foreign('animal_medicine_source_id', 'amsrctrans_amsrcid_foreign')->references('id')->on('animal_medicine_sources')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animal_medicine_source_translations');
    }
}
