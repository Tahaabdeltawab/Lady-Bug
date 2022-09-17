<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryFarmedTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country_farmed_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('farmed_type_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('common_name')->nullable();
            $table->boolean('popular')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('country_farmed_type');
    }
}
