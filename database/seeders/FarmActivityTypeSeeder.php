<?php

namespace Database\Seeders;

use App\Models\FarmActivityType;
use Illuminate\Database\Seeder;

class FarmActivityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FarmActivityType::create(['id' => 1, 'name' => ['ar' => 'محاصيل', 'en' => 'crops']]);
        FarmActivityType::create(['id' => 2, 'name' => ['ar' => 'أشجار', 'en' => 'trees']]);
        FarmActivityType::create(['id' => 3, 'name' => ['ar' => 'نباتات منزلية', 'en' => 'homeplants']]);
        FarmActivityType::create(['id' => 4, 'name' => ['ar' => 'حيوانات', 'en' => 'animals']]);
    }
}
