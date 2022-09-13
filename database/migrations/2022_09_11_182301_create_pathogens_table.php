<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePathogensTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pathogens', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->foreignId('pathogen_type_id')->nullable()->constrained();
            $table->string('bio_control')->nullable();
            $table->string('ch_control')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pathogens');
    }
}
