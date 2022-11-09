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
            $table->decimal('n')->nullable()->default(0);
            $table->decimal('p')->nullable()->default(0);
            $table->decimal('k')->nullable()->default(0);
            $table->decimal('fe')->nullable()->default(0);
            $table->decimal('b')->nullable()->default(0);
            $table->decimal('ca')->nullable()->default(0);
            $table->decimal('mg')->nullable()->default(0);
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
