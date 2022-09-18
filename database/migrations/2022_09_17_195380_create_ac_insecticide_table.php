<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcInsecticideTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ac_insecticide', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insecticide_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('ac_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ac_insecticide');
    }
}
