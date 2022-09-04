<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultancyProfileWorkFieldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultancy_profile_work_field', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultancy_profile_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('work_field_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rating_rating_question');
    }
}
