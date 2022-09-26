<?php

namespace Database\Seeders;

use App\Models\FarmedTypeStage;
use Illuminate\Database\Seeder;

class FarmedTypeStageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FarmedTypeStage::create(['id' => 1, 'name' => ['ar' => 'قبل الزراعة', 'en' => 'Pre-farming']]);
        FarmedTypeStage::create(['id' => 2, 'name' => ['ar' => 'الإنبات', 'en' => 'Seeds germination']]);
        FarmedTypeStage::create(['id' => 3, 'name' => ['ar' => 'الشتلات', 'en' => 'Seedlings']]);
        FarmedTypeStage::create(['id' => 4, 'name' => ['ar' => 'النمو', 'en' => 'Growth']]);
        FarmedTypeStage::create(['id' => 5, 'name' => ['ar' => 'قبل الإزهار', 'en' => 'Pre-flowering']]);
        FarmedTypeStage::create(['id' => 6, 'name' => ['ar' => 'الإزهار', 'en' => 'Flowering']]);
        FarmedTypeStage::create(['id' => 7, 'name' => ['ar' => 'النضج', 'en' => 'Maturity']]);
    }
}
