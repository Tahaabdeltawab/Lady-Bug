<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaltDetailsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salt_details', function (Blueprint $table) {
            $table->id();
            $table->string('saltable_type');
            $table->double('PH');
            $table->double('CO3');
            $table->double('HCO3');
            $table->double('Cl');
            $table->double('SO4');
            $table->double('Ca');
            $table->double('Mg');
            $table->double('K');
            $table->double('Na');
            $table->double('Na2CO3');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('salt_details');
    }
}
