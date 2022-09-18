<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiseaseFarmedTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disease_farmed_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disease_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('farmed_type_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('sensitive')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('disease_farmed_type');
    }
}
