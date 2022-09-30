<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultancyProfilesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultancy_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->integer('experience');
            $table->boolean('ar');
            $table->boolean('en');
            $table->boolean('free_consultancy');
            $table->decimal('consultancy_price');
            $table->decimal('month_consultancy_price');
            $table->decimal('year_consultancy_price');
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
        Schema::drop('consultancy_profiles');
    }
}
