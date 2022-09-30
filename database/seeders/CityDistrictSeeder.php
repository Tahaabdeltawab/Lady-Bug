<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\District;
use Illuminate\Database\Seeder;

class CityDistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        City::create(['id' => 1, 'name' => ['ar' => 'بني سويف', 'en' => 'Beni-suef']]);
        City::create(['id' => 2, 'name' => ['ar' => 'الجيزة', 'en' => 'Giza']]);
        City::create(['id' => 3, 'name' => ['ar' => 'القاهرة', 'en' => 'Cairo']]);

        District::create(['id' => 1, 'city_id' => 1, 'name' => ['ar' => 'ناصر', 'en' => 'Nasser']]);
        District::create(['id' => 2, 'city_id' => 1, 'name' => ['ar' => 'ببا', 'en' => 'Beba']]);
        District::create(['id' => 3, 'city_id' => 1, 'name' => ['ar' => 'إهناسيا', 'en' => 'Ihnasia']]);

        District::create(['id' => 4, 'city_id' => 2, 'name' => ['ar' => 'الدقي', 'en' => 'Dokki']]);
        District::create(['id' => 5, 'city_id' => 2, 'name' => ['ar' => 'التحرير', 'en' => 'Tahrir']]);

        District::create(['id' => 6, 'city_id' => 3, 'name' => ['ar' => 'التجمع الخامس', 'en' => '5th Settlement']]);
        District::create(['id' => 7, 'city_id' => 3, 'name' => ['ar' => 'مدينة نصر', 'en' => 'Nassr city']]);
    }
}
