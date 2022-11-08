<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddElementsToNutElemValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nut_elem_values', function (Blueprint $table) {
            $table->decimal('s')->nullable();
            $table->decimal('zn')->nullable();
            $table->decimal('mn')->nullable();
            $table->decimal('cu')->nullable();
            $table->decimal('cl')->nullable();
            $table->decimal('mo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nut_elem_values', function (Blueprint $table) {
            //
        });
    }
}
