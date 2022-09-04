<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkFieldTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_field_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->unsignedBigInteger('work_field_id');
            $table->unique(['work_field_id', 'locale'],'workfieldtrans_workfieldid_unique');
            $table->string('name');
            $table->string('description');
            $table->foreign('work_field_id', 'workfieldtrans_workfieldid_foreign')->references('id')->on('work_fields')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_field_translations');
    }
}
