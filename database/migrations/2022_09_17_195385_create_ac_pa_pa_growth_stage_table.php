<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcPaPaGrowthStageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_pa_pa_growth_stage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ac_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('pa_pa_growth_stage_id')->constrained('pa_pa_growth_stage')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('effect')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ac_pa_pa_growth_stage');
    }
}
