<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyingNoteTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buying_note_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->unsignedBigInteger('buying_note_id');
            $table->unique(['buying_note_id', 'locale'],'buynotetrans_buynoteid_unique');
            $table->text('content');
            $table->foreign('buying_note_id', 'buynotetrans_buynoteid_foreign')->references('id')->on('buying_notes')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('buying_note_translations');
    }
}
