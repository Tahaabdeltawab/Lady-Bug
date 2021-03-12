<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnimalBreedingPurposeTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animal_breeding_purpose_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->unsignedBigInteger('animal_breeding_purpose_id');
            $table->unique(['animal_breeding_purpose_id', 'locale'],'abptrans_abpid_unique');
            $table->string('name');
            $table->foreign('animal_breeding_purpose_id', 'abptrans_abpid_foreign')->references('id')->on('animal_breeding_purposes')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animal_breeding_purpose_translations');
    }
}
