<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmingWayTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farming_way_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->unsignedBigInteger('farming_way_id');
            $table->unique(['farming_way_id', 'locale'],'fwaytrans_fwayid_unique');
            $table->string('name');
            $table->foreign('farming_way_id', 'fwaytrans_fwayid_foreign')->references('id')->on('farming_ways')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farming_way_translations');
    }
}
