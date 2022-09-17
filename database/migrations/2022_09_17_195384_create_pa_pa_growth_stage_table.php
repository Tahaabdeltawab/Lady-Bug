<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaPaGrowthStageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pa_pa_growth_stage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pathogen_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('pathogen_growth_stage_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pa_pa_growth_stage');
    }
}
