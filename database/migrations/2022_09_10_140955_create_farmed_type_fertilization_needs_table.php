<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmedTypeFertilizationNeedsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmed_type_fertilization_needs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmed_type_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('nut_elem_value_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('stage', ['pre_farming', 'germination', 'seedling_farming', 'growth', 'pre_flowering', 'flowering', 'maturity'])->nullable();
            $table->enum('per', ['acre','tree'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('farmed_type_fertilization_needs');
    }
}
