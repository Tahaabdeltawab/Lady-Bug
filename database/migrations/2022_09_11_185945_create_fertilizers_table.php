<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFertilizersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fertilizers', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->foreignId('nut_elem_value_id')->nullable()->constrained();
            $table->enum('dosage_form', ['powder', 'liquid'])->nullable();
            $table->string('producer')->nullable();
            $table->foreignId('country_id')->nullable()->constrained();
            $table->enum('addition_way', ['leaves', 'soil'])->nullable();
            $table->string('conc')->nullable();
            $table->date('reg_date')->nullable();
            $table->string('reg_num')->nullable();
            $table->decimal('mix_ph')->nullable();
            $table->string('usage_rate')->nullable();
            $table->integer('expiry')->nullable();
            $table->json('precautions')->nullable();
            $table->json('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('fertilizers');
    }
}
