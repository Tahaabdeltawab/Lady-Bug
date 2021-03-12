<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmedTypeStageTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmed_type_stage_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->unsignedBigInteger('farmed_type_stage_id');
            $table->unique(['farmed_type_stage_id', 'locale'],'ftypestgtrans_ftypestgid_unique');
            $table->string('name');
            $table->foreign('farmed_type_stage_id', 'ftypestgtrans_ftypestgid_foreign')->references('id')->on('farmed_type_stages')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farmed_type_stage_translations');
    }
}
