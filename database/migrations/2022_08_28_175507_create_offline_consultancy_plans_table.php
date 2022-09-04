<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfflineConsultancyPlansTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_consultancy_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultancy_profile_id')->constrained();
            $table->string('address');
            $table->date('date');
            $table->decimal('visit_price');
            $table->decimal('year_price');
            $table->decimal('two_year_price');
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
        Schema::drop('offline_consultancy_plans');
    }
}
