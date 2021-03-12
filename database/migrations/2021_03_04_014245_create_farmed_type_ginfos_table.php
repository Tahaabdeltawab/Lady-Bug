<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmedTypeGinfosTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmed_type_ginfos', function (Blueprint $table) {
            $table->id();
            // $table->string('title');
            // $table->text('content');
            $table->foreignId('farmed_type_id')->constrained();
            $table->foreignId('farmed_type_stage_id')->constrained();
            $table->timestamps();
            $table->softDeletes();
            // $table->foreign('farmed_type_id')->references('id')->on('farmed_types')->onDelete('CASCADE');
            // $table->foreign('farmed_type_stage_id')->references('id')->on('farmed_type_stages')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('farmed_type_ginfos');
    }
}
