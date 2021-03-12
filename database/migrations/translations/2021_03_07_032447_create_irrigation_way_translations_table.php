<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIrrigationWayTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('irrigation_way_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->unsignedBigInteger('irrigation_way_id');
            $table->unique(['irrigation_way_id', 'locale'],'irrwaytrans_irrwayid_unique');
            $table->string('name');
            $table->foreign('irrigation_way_id', 'irrwaytrans_irrwayid_foreign')->references('id')->on('irrigation_ways')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('irrigation_way_translations');
    }
}
