<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farms', function (Blueprint $table) {
            $table->id();
            $table->boolean('real');
            $table->boolean('archived');
            $table->timestamp('farming_date');
            $table->double('farming_compatibility');
            $table->double('home_plant_pot_size')->nullable();
            $table->double('area')->nullable();
            $table->bigInteger('farmed_number')->nullable();
            $table->foreignId('admin_id')->constrained('users');
            $table->foreignId('area_unit_id')->nullable()->constrained('measuring_units');
            $table->foreignId('location_id')->constrained();
            $table->foreignId('farm_activity_type_id')->constrained();
            $table->foreignId('farmed_type_id')->constrained();
            $table->foreignId('farmed_type_class_id')->constrained();
            $table->foreignId('animal_breeding_purpose_id')->nullable()->constrained();
            $table->foreignId('home_plant_illuminating_source_id')->nullable()->constrained();
            $table->foreignId('farming_method_id')->nullable()->constrained();
            $table->foreignId('farming_way_id')->nullable()->constrained();
            $table->foreignId('irrigation_way_id')->nullable()->constrained();
            $table->foreignId('soil_type_id')->nullable()->constrained();
            $table->foreignId('soil_detail_id')->nullable()->constrained('chemical_details');
            $table->foreignId('irrigation_water_detail_id')->nullable()->constrained('chemical_details');
            $table->foreignId('animal_drink_water_salt_detail_id')->nullable()->constrained('salt_details');
            $table->timestamps();
            $table->softDeletes();
            // $table->foreign('farm_activity_type_id')->references('id')->on('farm_activity_types')->onDelete('CASCADE');
            // $table->foreign('farmed_type_id')->references('id')->on('farmed_types')->onDelete('CASCADE');
            // $table->foreign('farmed_type_class_id')->references('id')->on('farmed_type_classes')->onDelete('CASCADE');
            // $table->foreign('animal_breeding_purpose_id')->references('id')->on('animal_breeding_purposes')->onDelete('CASCADE');
            // $table->foreign('home_plant_illuminating_source_id')->references('id')->on('home_plant_illuminating_sources')->onDelete('CASCADE');
            // $table->foreign('farming_method_id')->references('id')->on('farming_methods')->onDelete('CASCADE');
            // $table->foreign('farming_way_id')->references('id')->on('farming_ways')->onDelete('CASCADE');
            // $table->foreign('irrigation_way_id')->references('id')->on('irrigation_ways')->onDelete('CASCADE');
            // $table->foreign('soil_type_id')->references('id')->on('soil_types')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farms');
    }
}
