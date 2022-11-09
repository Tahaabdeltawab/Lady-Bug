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
            $table->decimal('s')->nullable()->default(0);
            $table->decimal('zn')->nullable()->default(0);
            $table->decimal('mn')->nullable()->default(0);
            $table->decimal('cu')->nullable()->default(0);
            $table->decimal('cl')->nullable()->default(0);
            $table->decimal('mo')->nullable()->default(0);
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
