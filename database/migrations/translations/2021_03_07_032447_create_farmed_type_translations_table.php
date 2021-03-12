<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmedTypeTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmed_type_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->unsignedBigInteger('farmed_type_id');
            $table->unique(['farmed_type_id', 'locale'],'ftypetrans_ftypeid_unique');
            $table->string('name');
            $table->foreign('farmed_type_id', 'ftypetrans_ftypeid_foreign')->references('id')->on('farmed_types')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farmed_type_translations');
    }
}
