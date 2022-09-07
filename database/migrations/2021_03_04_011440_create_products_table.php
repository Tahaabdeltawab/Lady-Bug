<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->double('price');
            $table->json('description');
            $table->foreignId('seller_id')->constrained('users');
            $table->json('name');
            $table->foreignId('city_id')->constrained();
            $table->foreignId('district_id')->constrained();
            $table->string('seller_mobile');
            $table->boolean('sold');
            $table->string('other_links')->nullable();
            $table->timestamps();
            // $table->foreign('seller_id')->references('id')->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('products');
    }
}
