<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoilTypeTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soil_type_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->unsignedBigInteger('soil_type_id');
            $table->unique(['soil_type_id', 'locale'],'soiltypetrans_soiltypeid_unique');
            $table->string('name');
            $table->foreign('soil_type_id', 'soiltypetrans_soiltypeid_foreign')->references('id')->on('soil_types')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('soil_type_translations');
    }
}
