<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmedTypesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmed_types', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->foreignId('farm_activity_type_id')->constrained();
            $table->string('farming_temperature')->nullable();
            $table->string('flowering_temperature')->nullable();
            $table->string('maturity_temperature')->nullable();
            $table->string('humidity')->nullable();
            $table->integer('flowering_time')->nullable();
            $table->integer('maturity_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('farmed_types');
    }
}
