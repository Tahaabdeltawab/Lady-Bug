<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('marketing_datas', function (Blueprint $table) {
            $table->foreign('country_id','country_marketing_foreign')->references('id')->on('countries');
        });
        Schema::table('farmed_type_extras', function (Blueprint $table) {
            $table->foreign('farmed_type_id','farmedtype_extra_foreign')->references('id')->on('farmed_types')
            ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('irrigation_rate_id','irrate_extra_foreign')->references('id')->on('irrigation_rates');
        });
    }
}
