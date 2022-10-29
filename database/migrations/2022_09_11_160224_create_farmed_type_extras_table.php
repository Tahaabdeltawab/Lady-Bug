<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmedTypeExtrasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmed_type_extras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmed_type_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('irrigation_rate_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('seedling_type', ['seedlings', 'seeds'])->nullable();
            $table->string('scientific_name')->nullable();
            $table->string('history')->nullable();
            $table->string('producer')->nullable();
            $table->string('description')->nullable();
            $table->integer('cold_hours')->nullable();
            $table->integer('illumination_hours')->nullable();
            $table->decimal('seeds_rate')->nullable();
            $table->decimal('production_rate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('farmed_type_extras');
    }
}
