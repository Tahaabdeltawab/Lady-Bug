<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workables', function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('worker_id')->constrained('users');
            $table->foreignId('workable_id');
            $table->string('workable_type');
            $table->boolean('status')->nullable();
            $table->timestamps();
            $table->softDeletes();

            /* $table->foreign('worker_id')
                ->references('id')
                ->on('users')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE'); */
            $table->unique(['worker_id', 'workable_id', 'workable_type'], 'wworker_w_wtype_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workables');
    }
}
