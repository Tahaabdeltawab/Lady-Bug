<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmedTypeGinfoTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmed_type_ginfo_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->unsignedBigInteger('farmed_type_ginfo_id');
            $table->unique(['farmed_type_ginfo_id', 'locale'],'ftypeginfotrans_ftypeginfoid_unique');
            $table->string('title');
            $table->text('content');
            $table->foreign('farmed_type_ginfo_id', 'ftypeginfotrans_ftypeginfoid_foreign')->references('id')->on('farmed_type_ginfos')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farmed_type_ginfo_translations');
    }
}
