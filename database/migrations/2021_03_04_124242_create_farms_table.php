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
            $table->date('farming_date');
            $table->text('farming_compatibility')->nullable();
            $table->foreignId('home_plant_pot_size_id')->nullable()->constrained();
            $table->double('area')->nullable();
            $table->bigInteger('farmed_number')->nullable();
            $table->foreignId('admin_id')->constrained('users');
            $table->foreignId('area_unit_id')->nullable()->constrained('measuring_units');
            $table->foreignId('location_id')->nullable()->constrained()/* ->onDelete('CASCADE')->onUpdate('CASCADE') */;
            $table->foreignId('farm_activity_type_id')->constrained();
            $table->foreignId('farmed_type_id')->constrained();
            $table->foreignId('farmed_type_class_id')->nullable()->constrained();
            $table->foreignId('animal_breeding_purpose_id')->nullable()->constrained();
            $table->foreignId('home_plant_illuminating_source_id')->nullable()->constrained();
            $table->foreignId('farming_method_id')->nullable()->constrained();
            $table->foreignId('farming_way_id')->nullable()->constrained();
            $table->foreignId('irrigation_way_id')->nullable()->constrained();
            $table->foreignId('soil_type_id')->nullable()->constrained();
            $table->foreignId('soil_detail_id')->nullable()->constrained('chemical_details')/* ->onDelete('CASCADE')->onUpdate('CASCADE') */;
            $table->foreignId('irrigation_water_detail_id')->nullable()->constrained('chemical_details')/* ->onDelete('CASCADE')->onUpdate('CASCADE') */;
            $table->foreignId('animal_drink_water_salt_detail_id')->nullable()->constrained('salt_details')/* ->onDelete('CASCADE')->onUpdate('CASCADE') */;
            $table->timestamps();
            /**
             * todo: all commented foreign ids cascades [animal_drink_water_salt_detail_id,irrigation_water_detail_id,soil_detail_id,location_id]
             * todo: the foreign keys should be reversed: put farm_id on the linked tables and enable cascades
             * ! the current state means if salt_details , for example, was deleted, the farm will be deleted
             */
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
