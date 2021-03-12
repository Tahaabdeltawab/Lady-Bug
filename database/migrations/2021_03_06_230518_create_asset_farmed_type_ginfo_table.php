<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetFarmedTypeGinfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_farmed_type_ginfo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('farmed_type_ginfo_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->timestamps();

            /* $table->foreign('asset_id')->references('id')->on('assets')
                ->onDelete('cascade');
            $table->foreign('farmed_type_ginfo_id')->references('id')->on('farmed_type_ginfos')
                ->onDelete('cascade'); */

            $table->unique(['asset_id', 'farmed_type_ginfo_id'],'asset_ftginfo_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_farmed_type_ginfo');
    }
}
