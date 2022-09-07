<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChemicalDetailsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chemical_details', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->double('acidity_value');
            $table->foreignId('acidity_unit_id')->constrained('measuring_units');
            $table->foreignId('acidity_type_id')->constrained();
            $table->foreignId('salt_type_id')->constrained('salt_types');
            $table->double('salt_concentration_value');
            $table->foreignId('salt_concentration_unit_id')->nullable()->constrained('measuring_units');
            $table->foreignId('salt_detail_id')->constrained();
            $table->timestamps();
            // $table->foreign('salt_detail_id')->references('id')->on('salt_details')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('chemical_details');
    }
}
