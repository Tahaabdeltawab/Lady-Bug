<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkablePermissionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workable_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('workable_type_id')->nullable()->constrained();
            $table->timestamps();
            $table->softDeletes();

           /*  $table->foreign('workable_type_id')
                ->references('id')
                ->on('workable_types')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE'); */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('workable_permissions');
    }
}
