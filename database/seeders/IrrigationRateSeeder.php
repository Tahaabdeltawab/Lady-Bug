<?php

namespace Database\Seeders;

use App\Models\IrrigationRate;
use Illuminate\Database\Seeder;

class IrrigationRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        IrrigationRate::create(['id' => 1, 'name' => ['ar' => 'غزير', 'en' => 'Heavy']]);
        IrrigationRate::create(['id' => 2, 'name' => ['ar' => 'متوسط', 'en' => 'Medium']]);
        IrrigationRate::create(['id' => 3, 'name' => ['ar' => 'قليل', 'en' => 'Little']]);
        IrrigationRate::create(['id' => 4, 'name' => ['ar' => 'نادر', 'en' => 'Rare']]);
    }
}
