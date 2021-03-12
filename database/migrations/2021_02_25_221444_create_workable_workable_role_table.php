<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkableWorkableRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table              = "workable_workable_role";

        Schema::create($table, function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('workable_role_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE')->onDelete('CASCADE');
            $table->foreignId('workable_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE')->onDelete('CASCADE');
            $table->boolean('status')->nullable();
            $table->timestamps();

           /*  $table->foreign('workable_role_id')
                ->references('id')
                ->on('workable_roles')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('workable_id')
                ->references('id')
                ->on('workables')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE'); */

            $table->unique(['workable_id', 'workable_role_id'],'w_wrole_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workable_workable_role');
    }
}
