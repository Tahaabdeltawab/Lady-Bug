<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceTablesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_tables', function (Blueprint $table) {
            $table->id();
            // $table->string('name');
            $table->foreignId('farm_id')->constrained();
            $table->timestamps();
            $table->softDeletes();
            // $table->foreign('farm_id')->references('id')->on('farms')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('service_tables');
    }
}
