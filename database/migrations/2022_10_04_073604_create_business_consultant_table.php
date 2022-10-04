<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessConsultantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_consultant', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_user_id')->constrained('role_user')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('plan_id')->nullable()->constrained('offline_consultancy_plans'); // if offline plan
            $table->string('period');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('business_consultant');
    }
}
