<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataToFarmedTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farmed_types', function (Blueprint $table) {
            $table->string('suitable_soil_salts_concentration')->nullable();
            $table->string('suitable_water_salts_concentration')->nullable();
            $table->string('suitable_ph')->nullable();
            $table->string('suitable_soil_types')->nullable()->constrained('soil_types');
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
            if (
                Schema::hasColumn('farmed_types', 'suitable_soil_salts_concentration') &&
                Schema::hasColumn('farmed_types', 'suitable_water_salts_concentration') &&
                Schema::hasColumn('farmed_types', 'suitable_ph') &&
                Schema::hasColumn('farmed_types', 'suitable_soil_types')
                )
            {
                $table->dropColumn('suitable_soil_salts_concentration');
                $table->dropColumn('suitable_water_salts_concentration');
                $table->dropColumn('suitable_ph');
                $table->dropColumn('suitable_soil_types');
            }
        });
    }
}
