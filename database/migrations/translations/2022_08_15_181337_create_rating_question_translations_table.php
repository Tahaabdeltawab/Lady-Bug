<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingQuestionTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rating_question_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->unsignedBigInteger('rating_question_id');
            $table->unique(['rating_question_id', 'locale'],'ratingquestiontrans_ratingquestionid_unique');
            $table->string('name');
            $table->string('description');
            $table->foreign('rating_question_id', 'ratingquestiontrans_ratingquestionid_foreign')->references('id')->on('rating_questions')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rating_question_translations');
    }
}
