<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiseaseRegistrationsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disease_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('disease_id')->nullable()->constrained();
            $table->string('expected_name')->nullable();
            $table->boolean('status')->default(0);
            $table->date('discovery_date')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('farm_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('farm_report_id')->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('infection_rate_id')->nullable()->constrained();
            $table->string('lat')->nullable();
            $table->string('lon')->nullable();
            $table->foreignId('country_id')->nullable()->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('disease_registrations');
    }
}
