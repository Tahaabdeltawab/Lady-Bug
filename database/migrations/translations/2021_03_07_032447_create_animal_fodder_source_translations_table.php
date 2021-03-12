<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnimalFodderSourceTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animal_fodder_source_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->unsignedBigInteger('animal_fodder_source_id');
            $table->unique(['animal_fodder_source_id', 'locale'],'afsrctrans_afsrcid_unique');
            $table->string('name');
            $table->foreign('animal_fodder_source_id', 'afsrctrans_afsrcid_foreign')->references('id')->on('animal_fodder_sources')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animal_fodder_source_translations');
    }
}
