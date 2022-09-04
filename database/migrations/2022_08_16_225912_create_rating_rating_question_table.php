<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingRatingQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rating_rating_question', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rating_id')->nullable()->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('rating_question_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('rateable_id')->nullable()->constrained('users')->onUpdate('CASCADE')->onDelete('CASCADE');
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
