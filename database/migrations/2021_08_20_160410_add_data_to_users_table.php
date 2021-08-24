<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('provider')->nullable();
            $table->string('fcm')->nullable();
            $table->string('avatar')->nullable();
            $table->string('code')->nullable();
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
            if (Schema::hasColumn('users', 'provider'))
                $table->dropColumn('provider');
            if (Schema::hasColumn('users', 'fcm'))
                $table->dropColumn('fcm');
            if (Schema::hasColumn('users', 'avatar'))
                $table->dropColumn('avatar');
            if (Schema::hasColumn('users', 'code'))
                $table->dropColumn('code');
        });
    }
}
