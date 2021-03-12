<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkablePermissionWorkableRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table              = "workable_permission_workable_role";

        Schema::create($table, function (Blueprint $table)
        {
            $table->id();
            $table->foreignId('workable_role_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('workable_permission_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');

            /* $table->foreign('workable_role_id')
                ->references('id')
                ->on('workable_roles')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE');

            $table->foreign('workable_permission_id')
                ->references('id')
                ->on('workable_permissions')
                ->onUpdate('CASCADE')
                ->onDelete('CASCADE'); */

            $table->unique(['workable_role_id', 'workable_permission_id'], 'wrole_wpermission_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workable_permission_workable_role');
    }
}
