<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaskTypeTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_type_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->unsignedBigInteger('task_type_id');
            $table->unique(['task_type_id', 'locale'],'tasktypetrans_tasktypeid_unique');
            $table->string('name');
            $table->foreign('task_type_id', 'tasktypetrans_tasktypeid_foreign')->references('id')->on('task_types')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_type_translations');
    }
}
