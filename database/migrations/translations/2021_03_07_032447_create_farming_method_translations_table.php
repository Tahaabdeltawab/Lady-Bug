<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmingMethodTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farming_method_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->unsignedBigInteger('farming_method_id');
            $table->unique(['farming_method_id', 'locale'],'fmthdtrans_fmthdid_unique');
            $table->string('name');
            $table->foreign('farming_method_id', 'fmthdtrans_fmthdid_foreign')->references('id')->on('farming_methods')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farming_method_translations');
    }
}
