<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceTasksTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamp('start_at')->nullable();
            $table->timestamp('notify_at')->nullable();
            $table->foreignId('farm_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('service_table_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('task_type_id')->constrained();
            $table->double('quantity');
            $table->foreignId('quantity_unit_id')->nullable()->constrained('measuring_units');
            $table->timestamp('due_at')->nullable();
            $table->boolean('done')->default(0);
            $table->timestamps();
            // $table->foreign('farm_id')->references('id')->on('farms')->onDelete('CASCADE');
            // $table->foreign('service_table_id')->references('id')->on('service_tables')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('service_tasks');
    }
}
