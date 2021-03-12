<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnimalFodderTypeFarmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animal_fodder_type_farm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_fodder_type_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('farm_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->timestamps();

            /* $table->foreign('animal_fodder_type_id')->references('id')->on('animal_fodder_types')
                ->onDelete('cascade');
            $table->foreign('farm_id')->references('id')->on('farms')
                ->onDelete('cascade');
 */
            $table->unique(['animal_fodder_type_id', 'farm_id'],'foddert_farm_unique');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animal_fodder_type_farm');
    }
}
