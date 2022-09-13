<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiseaseCausativesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disease_causatives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disease_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('temp_gt')->nullable();
            $table->decimal('temp_lt')->nullable();
            $table->decimal('humidity_gt')->nullable();
            $table->decimal('humidity_lt')->nullable();
            $table->decimal('ph_gt')->nullable();
            $table->decimal('ph_lt')->nullable();
            $table->decimal('soil_salts_gt')->nullable();
            $table->decimal('soil_salts_lt')->nullable();
            $table->decimal('water_salts_gt')->nullable();
            $table->decimal('water_salts_lt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('disease_causatives');
    }
}
