<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceTaskTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_task_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->unsignedBigInteger('service_task_id');
            $table->unique(['service_task_id', 'locale'],'servicetasktrans_servicetaskid_unique');
            $table->string('name');
            $table->foreign('service_task_id', 'servicetasktrans_servicetaskid_foreign')->references('id')->on('service_tasks')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_task_translations');
    }
}
