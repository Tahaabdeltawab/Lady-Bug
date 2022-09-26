<?php

namespace Database\Seeders;

use App\Models\WorkField;
use Illuminate\Database\Seeder;

class WorkFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WorkField::create(['name' => ['ar' => 'نخيل', 'en' => 'Palm Trees']]);
        WorkField::create(['name' => ['ar' => 'الطماطم', 'en' => 'Tomatoes']]);
        WorkField::create(['name' => ['ar' => 'المانجو', 'en' => 'Mango']]);
        WorkField::create(['name' => ['ar' => 'أشجار الزيتون', 'en' => 'Olive Trees']]);
        WorkField::create(['name' => ['ar' => 'شبكات الري', 'en' => 'Irrigation Networks']]);
    }
}
