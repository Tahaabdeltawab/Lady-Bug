<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->foreignId('commenter_id')->constrained('users');
            $table->foreignId('parent_id')->constrained('comments')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('post_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->timestamps();
            $table->softDeletes();
            // $table->foreign('commenter_id')->references('id')->on('users')->onDelete('CASCADE');
            // $table->foreign('post_id')->references('id')->on('posts')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
