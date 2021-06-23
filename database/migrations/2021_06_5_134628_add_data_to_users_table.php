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
            $table->integer('income')->nullable();
            $table->date('dob')->nullable();
            $table->foreignId('city_id')->nullable()->constrained();
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
            if (Schema::hasColumn('users', 'income') && Schema::hasColumn('users', 'dob') && Schema::hasColumn('users', 'city_id'))
            {
                $table->dropColumn('income');
                $table->dropColumn('dob');
                $table->dropColumn('city_id');
            }
        });
    }
}
