<?php

namespace Database\Seeders;

use App\Models\InfectionRate;
use Illuminate\Database\Seeder;

class InfectionRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        InfectionRate::create(['id' => 1, 'name' => ['ar' => 'بسيطة', 'en' => 'Mild']]);
        InfectionRate::create(['id' => 2, 'name' => ['ar' => 'متوسطة', 'en' => 'Medium']]);
        InfectionRate::create(['id' => 3, 'name' => ['ar' => 'شديدة', 'en' => 'Severe']]);
        InfectionRate::create(['id' => 4, 'name' => ['ar' => 'متأخرة', 'en' => 'Late']]);
    }
}
