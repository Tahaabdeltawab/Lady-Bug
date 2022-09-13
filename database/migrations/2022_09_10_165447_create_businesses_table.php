<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('business_field_id')->constrained();
            $table->string('description')->nullable();
            $table->string('main_img')->nullable();
            $table->string('cover_img')->nullable();
            $table->string('com_name')->nullable();
            $table->string('status')->nullable();
            $table->string('mobile')->nullable();
            $table->string('whatsapp')->nullable();
            $table->decimal('lat')->nullable();
            $table->decimal('lon')->nullable();
            $table->foreignId('country_id')->nullable()->constrained();
            $table->boolean('privacy')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('businesses');
    }
}