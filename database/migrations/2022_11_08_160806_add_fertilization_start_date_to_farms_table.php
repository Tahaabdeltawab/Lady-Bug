<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFertilizationStartDateToFarmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farms', function (Blueprint $table) {
            $table->date('fertilization_start_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('farms', function (Blueprint $table) {
            if (Schema::hasColumn('farms', 'fertilization_start_date'))
            $table->dropColumn('fertilization_start_date');
        });
    }
}
