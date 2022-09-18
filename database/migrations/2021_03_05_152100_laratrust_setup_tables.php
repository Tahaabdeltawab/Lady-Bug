<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LaratrustSetupTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return  void
     */
    public function up()
    {
        // Create table for storing roles
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for storing permissions
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for storing teams
        Schema::create('teams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Create table for associating roles to users and teams (Many To Many Polymorphic)
        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('user_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->string('user_type');
            $table->foreignId('business_id')->nullable()->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->unique(['user_id', 'role_id', 'user_type', 'business_id'],'u_role_utype_f_unique');
        });

        // Create table for associating permissions to users (Many To Many Polymorphic)
        Schema::create('permission_user', function (Blueprint $table) {
            $table->foreignId('permission_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('user_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->string('user_type');
            $table->foreignId('business_id')->nullable()->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->unique(['user_id', 'permission_id', 'user_type', 'business_id'],'u_perm_utype_f_unique');
        });

        // Create table for associating permissions to roles (Many-to-Many)
        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignId('permission_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('role_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->primary(['permission_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return  void
     */
    public function down()
    {
        Schema::dropIfExists('permission_user');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('teams');
    }
}
