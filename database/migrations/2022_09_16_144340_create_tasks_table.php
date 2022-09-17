<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_report_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('farm_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('business_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->date('date')->nullable();
            $table->integer('week')->nullable();
            $table->foreignId('task_type_id')->nullable()->constrained();
            $table->foreignId('insecticide_id')->nullable()->constrained();
            $table->foreignId('fertilizer_id')->nullable()->constrained();
            $table->decimal('quantity')->nullable();
            $table->string('quantity_unit')->nullable();
            $table->boolean('done')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tasks');
    }
}
