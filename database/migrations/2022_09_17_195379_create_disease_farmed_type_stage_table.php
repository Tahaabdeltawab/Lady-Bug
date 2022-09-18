<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiseaseFarmedTypeStageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disease_farmed_type_stage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disease_farmed_type_id')->constrained('disease_farmed_type')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('farmed_type_stage_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disease_farmed_type_stage');
    }
}
