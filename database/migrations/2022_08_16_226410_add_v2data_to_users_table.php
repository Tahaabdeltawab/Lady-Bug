<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddV2dataToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('bio')->nullable();
            $table->decimal('balance')->nullable();
            $table->boolean('marital_status')->nullable();
            $table->boolean('is_consultant')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'bio'))
                $table->dropColumn('bio');
            if (Schema::hasColumn('users', 'balance'))
                $table->dropColumn('balance');
            if (Schema::hasColumn('users', 'marital_status'))
                $table->dropColumn('marital_status');
            if (Schema::hasColumn('users', 'is_consultant'))
                $table->dropColumn('is_consultant');
        });
    }
}
