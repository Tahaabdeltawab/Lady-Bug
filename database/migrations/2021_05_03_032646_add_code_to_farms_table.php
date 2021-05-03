<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCodeToFarmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('farms', function (Blueprint $table) {
            $table->string('code')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('farms', 'code'))
        {
            Schema::table('farms', function (Blueprint $table)
            {
                $table->dropColumn('code');
            });
        }
    }
}
