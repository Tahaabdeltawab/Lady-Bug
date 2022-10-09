<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRatingDataToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('id_verified')->default(0);
            $table->boolean('made_transaction')->default(0);
            $table->boolean('met_ladybug')->default(0);
            $table->boolean('reactive')->default(0);
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
            if (Schema::hasColumn('users', 'id_verified'))
                $table->dropColumn('id_verified');
            if (Schema::hasColumn('users', 'made_transaction'))
                $table->dropColumn('made_transaction');
            if (Schema::hasColumn('users', 'met_ladybug'))
                $table->dropColumn('met_ladybug');
            if (Schema::hasColumn('users', 'reactive'))
                $table->dropColumn('reactive');
        });
    }
}
