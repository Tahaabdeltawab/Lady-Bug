<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMarketingDatasTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_datas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmed_type_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('year')->nullable();
            $table->bigInteger('country_id')->nullable();
            $table->decimal('production')->nullable();
            $table->decimal('consumption')->nullable();
            $table->decimal('export')->nullable();
            $table->decimal('price_avg')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('marketing_datas');
    }
}
