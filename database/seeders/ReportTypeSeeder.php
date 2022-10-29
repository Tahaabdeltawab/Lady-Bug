<?php

namespace Database\Seeders;

use App\Models\ReportType;
use Illuminate\Database\Seeder;

class ReportTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReportType::create(['id' => 1, 'name' => ['ar' => 'محتوى غير مناسب', 'en' => 'Non Suitable Content']]);
        ReportType::create(['id' => 2, 'name' => ['ar' => 'محتوى عنصري', 'en' => 'Racial Content']]);
        ReportType::create(['id' => 3, 'name' => ['ar' => 'محتوى جنسي', 'en' => 'Sexual Content']]);
    }
}
