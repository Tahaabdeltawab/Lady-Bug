<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNutElemValuesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nut_elem_values', function (Blueprint $table) {
            $table->id();
            $table->decimal('n')->nullable();
            $table->decimal('p')->nullable();
            $table->decimal('k')->nullable();
            $table->decimal('fe')->nullable();
            $table->decimal('b')->nullable();
            $table->decimal('ca')->nullable();
            $table->decimal('mg')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('nut_elem_values');
    }
}
