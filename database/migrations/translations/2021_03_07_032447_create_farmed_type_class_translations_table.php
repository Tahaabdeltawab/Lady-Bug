<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmedTypeClassTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmed_type_class_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->unsignedBigInteger('farmed_type_class_id');
            $table->unique(['farmed_type_class_id', 'locale'],'ftypeclstrans_ftypeclsid_unique');
            $table->string('name');
            $table->foreign('farmed_type_class_id', 'ftypeclstrans_ftypeclsid_foreign')->references('id')->on('farmed_type_classes')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farmed_type_class_translations');
    }
}
