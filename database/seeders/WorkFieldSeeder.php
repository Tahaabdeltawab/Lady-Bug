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
        WorkField::create(['id' => 1, 'name' => ['ar' => 'نخيل', 'en' => 'Palm Trees']]);
        WorkField::create(['id' => 2, 'name' => ['ar' => 'الطماطم', 'en' => 'Tomatoes']]);
        WorkField::create(['id' => 3, 'name' => ['ar' => 'المانجو', 'en' => 'Mango']]);
        WorkField::create(['id' => 4, 'name' => ['ar' => 'أشجار الزيتون', 'en' => 'Olive Trees']]);
        WorkField::create(['id' => 5, 'name' => ['ar' => 'شبكات الري', 'en' => 'Irrigation Networks']]);
    }
}
