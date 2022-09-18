<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiseasePathogenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disease_pathogen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disease_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('pathogen_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disease_pathogen');
    }
}
