<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetPostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asset_post', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('post_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->timestamps();

            /* $table->foreign('asset_id')->references('id')->on('assets')
                ->onDelete('cascade');
            $table->foreign('post_id')->references('id')->on('posts')
                ->onDelete('cascade'); */

            $table->unique(['asset_id', 'post_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_post');
    }
}
