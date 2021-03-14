<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcidityTypeTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acidity_type_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->unsignedBigInteger('acidity_type_id');
            $table->unique(['acidity_type_id', 'locale'],'acidityTTrans_acidityTid_unique');
            $table->string('name');
            $table->foreign('acidity_type_id', 'acidityTTrans_acidityTid_foreign')->references('id')->on('acidity_types')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acidity_type_translations');
    }
}
