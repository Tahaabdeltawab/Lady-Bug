<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChemicalFertilizerSourceTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chemical_fertilizer_source_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->unsignedBigInteger('chemical_fertilizer_source_id');
            $table->unique(['chemical_fertilizer_source_id', 'locale'],'chfsrctrans_chfsrcid_unique');
            $table->string('name');
            $table->foreign('chemical_fertilizer_source_id', 'chfsrctrans_chfsrcid_foreign')->references('id')->on('chemical_fertilizer_sources')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chemical_fertilizer_source_translations');
    }
}
