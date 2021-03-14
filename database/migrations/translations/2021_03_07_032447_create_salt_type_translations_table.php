<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaltTypeTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salt_type_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->unsignedBigInteger('salt_type_id');
            $table->unique(['salt_type_id', 'locale'],'saltTTrans_saltTid_unique');
            $table->string('name');
            $table->foreign('salt_type_id', 'saltTTrans_saltTid_foreign')->references('id')->on('salt_types')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salt_type_translations');
    }
}
