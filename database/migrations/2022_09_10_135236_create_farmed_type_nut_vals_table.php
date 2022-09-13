<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmedTypeNutValsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmed_type_nut_vals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmed_type_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('calories')->nullable();
            $table->decimal('total_fat')->nullable();
            $table->decimal('sat_fat')->nullable();
            $table->decimal('cholesterol')->nullable();
            $table->decimal('na')->nullable();
            $table->decimal('k')->nullable();
            $table->decimal('total_carb')->nullable();
            $table->decimal('dietary_fiber')->nullable();
            $table->decimal('sugar')->nullable();
            $table->decimal('protein')->nullable();
            $table->decimal('v_c')->nullable();
            $table->decimal('fe')->nullable();
            $table->decimal('v_b6')->nullable();
            $table->decimal('mg')->nullable();
            $table->decimal('ca')->nullable();
            $table->decimal('v_d')->nullable();
            $table->decimal('cobalamin')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('farmed_type_nut_vals');
    }
}
