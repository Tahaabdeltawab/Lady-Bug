<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddParentIdToFarmedTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farmed_types', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->constrained('farmed_types');
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
            if (Schema::hasColumn('farmed_types', 'business_id'))
            {
                $table->dropColumn('parent_id');
            }
        });
    }
}
