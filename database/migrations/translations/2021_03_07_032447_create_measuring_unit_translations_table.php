<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeasuringUnitTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measuring_unit_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->unsignedBigInteger('measuring_unit_id');
            $table->unique(['measuring_unit_id', 'locale'],'munittrans_munitid_unique');
            $table->string('name');
            $table->foreign('measuring_unit_id', 'munittrans_munitid_foreign')->references('id')->on('measuring_units')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('measuring_unit_translations');
    }
}
