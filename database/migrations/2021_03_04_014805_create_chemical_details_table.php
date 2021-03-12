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
            $table->enum('acidity', ['acidic', 'basic', 'neutral']);
            $table->double('acidity_value');
            $table->foreignId('acidity_unit_id')->constrained('measuring_units');
            $table->string('salt_type');
            $table->double('salt_concentration_value');
            $table->foreignId('salt_concentration_unit_id')->constrained('measuring_units');
            $table->foreignId('salt_detail_id')->constrained();
            $table->timestamps();
            $table->softDeletes();
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
