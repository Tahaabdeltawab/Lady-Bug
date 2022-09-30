<?php

namespace Database\Seeders;

use App\Models\ProductType;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductType::create(['id' => 1, 'name' => ['ar' => 'مبيد', 'en' => 'Insecticide']]);
        ProductType::create(['id' => 2, 'name' => ['ar' => 'معدات', 'en' => 'Equipment']]);
        ProductType::create(['id' => 3, 'name' => ['ar' => 'سماد', 'en' => 'Fertilizer']]);
        ProductType::create(['id' => 4, 'name' => ['ar' => 'غذائي', 'en' => 'Nutritional']]);
        ProductType::create(['id' => 5, 'name' => ['ar' => 'علف', 'en' => 'Fodder']]);
        ProductType::create(['id' => 6, 'name' => ['ar' => 'شتلات', 'en' => 'Seedlings']]);
        ProductType::create(['id' => 7, 'name' => ['ar' => 'بذور', 'en' => 'Seeds']]);
    }
}
