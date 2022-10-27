<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcPaGrowthStageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_pa_growth_stage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ac_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('pathogen_growth_stage_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->json('effect')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ac_pa_growth_stage');
    }
}
