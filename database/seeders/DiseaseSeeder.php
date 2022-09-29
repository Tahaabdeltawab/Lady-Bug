<?php

namespace Database\Seeders;

use App\Models\Disease;
use Illuminate\Database\Seeder;

class DiseaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Disease::create(['id' => 1, 'name' => ['ar' => '1 مرض', 'en' => 'Disease 1'], 'description' => ['ar' => '1 مرض', 'en' => 'Disease 1']]);
        Disease::create(['id' => 2, 'name' => ['ar' => '2 مرض', 'en' => 'Disease 2'], 'description' => ['ar' => '2 مرض', 'en' => 'Disease 2']]);
        Disease::create(['id' => 3, 'name' => ['ar' => '3 مرض', 'en' => 'Disease 3'], 'description' => ['ar' => '3 مرض', 'en' => 'Disease 3']]);
        Disease::create(['id' => 4, 'name' => ['ar' => 'مرض 4', 'en' => 'Disease 4'], 'description' => ['ar' => 'مرض 4', 'en' => 'Disease 4']]);
    }
}
