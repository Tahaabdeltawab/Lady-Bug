<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmSeedlingSourceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farm_seedling_source', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seedling_source_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('farm_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->timestamps();

            /* $table->foreign('seedling_source_id')->references('id')->on('seedling_sources')
                ->onDelete('cascade');
            $table->foreign('farm_id')->references('id')->on('farms')
                ->onDelete('cascade');
 */
            $table->unique(['seedling_source_id', 'farm_id'],'sdlngsrc_farm_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farm_seedling_source');
    }
}
