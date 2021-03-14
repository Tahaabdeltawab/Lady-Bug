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
            // $table->string('name');
            $table->foreignId('farm_activity_type_id')->constrained();
            $table->foreignId('photo_id')->constrained('assets');
            $table->timestamps();
            $table->softDeletes();
            // $table->foreign('farm_activity_type_id')->references('id')->on('farm_activity_types')->onDelete('CASCADE');
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
