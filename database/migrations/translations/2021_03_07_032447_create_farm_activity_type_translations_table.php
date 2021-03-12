<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmActivityTypeTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farm_activity_type_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->unsignedBigInteger('farm_activity_type_id');
            $table->unique(['farm_activity_type_id', 'locale'],'facttypetrans_facttypeid_unique');
            $table->string('name');
            $table->foreign('farm_activity_type_id', 'facttypetrans_facttypeid_foreign')->references('id')->on('farm_activity_types')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farm_activity_type_translations');
    }
}
