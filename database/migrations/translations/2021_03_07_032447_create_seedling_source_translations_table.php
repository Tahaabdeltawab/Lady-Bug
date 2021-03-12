<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeedlingSourceTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seedling_source_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->unsignedBigInteger('seedling_source_id');
            $table->unique(['seedling_source_id', 'locale'],'sdlngsrctrans_sdlngsrcid_unique');
            $table->string('name');
            $table->foreign('seedling_source_id', 'sdlngsrctrans_sdlngsrcid_foreign')->references('id')->on('seedling_sources')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seedling_source_translations');
    }
}
