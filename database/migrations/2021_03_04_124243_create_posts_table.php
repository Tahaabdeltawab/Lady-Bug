<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->foreignId('author_id')->constrained('users');
            $table->foreignId('farm_id')->constrained();
            $table->foreignId('farmed_type_id')->constrained();
            $table->foreignId('post_type_id')->constrained();
            $table->boolean('solved');
            $table->timestamps();
            $table->softDeletes();
            // $table->foreign('author_id')->references('id')->on('users')->onDelete('CASCADE');
            // $table->foreign('farm_id')->references('id')->on('farms')->onDelete('CASCADE');
            // $table->foreign('farmed_type_id')->references('id')->on('farmed_types')->onDelete('CASCADE');
            // $table->foreign('post_type_id')->references('id')->on('post_types')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('posts');
    }
}
