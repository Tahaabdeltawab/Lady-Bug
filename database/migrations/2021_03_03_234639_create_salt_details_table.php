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
            $table->string('saltable_type')->nullable();
            $table->double('PH')->nullable();
            $table->double('CO3')->nullable();
            $table->double('HCO3')->nullable();
            $table->double('Cl')->nullable();
            $table->double('SO4')->nullable();
            $table->double('Ca')->nullable();
            $table->double('Mg')->nullable();
            $table->double('K')->nullable();
            $table->double('Na')->nullable();
            $table->double('Na2CO3')->nullable();
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
