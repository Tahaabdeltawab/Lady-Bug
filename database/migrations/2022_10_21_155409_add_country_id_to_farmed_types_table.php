<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountryIdToFarmedTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farmed_types', function (Blueprint $table) {
            $table->foreignId('country_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('farmed_types', function (Blueprint $table) {
            if (Schema::hasColumn('farmed_types', 'country_id'))
                $table->dropColumn('country_id');
        });
    }
}
