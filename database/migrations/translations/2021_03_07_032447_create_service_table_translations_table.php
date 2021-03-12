<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceTableTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_table_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->unsignedBigInteger('service_table_id');
            $table->unique(['service_table_id', 'locale'],'servicetabletrans_servicetableid_unique');
            $table->string('name');
            $table->foreign('service_table_id', 'servicetabletrans_servicetableid_foreign')->references('id')->on('service_tables')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_table_translations');
    }
}
