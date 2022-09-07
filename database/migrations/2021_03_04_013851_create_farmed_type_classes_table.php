<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmedTypeClassesTable extends Migration
{
    

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmed_type_classes', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->foreignId('farmed_type_id')->constrained();
            $table->timestamps();
            // $table->foreign('farmed_type_id')->references('id')->on('farmed_types')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('farmed_type_classes');
    }
}
