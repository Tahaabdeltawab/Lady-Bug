<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::create(['id' => 1, 'name' => ['ar' => 'مصر', 'en' => 'Egypt']]);
        Country::create(['id' => 2, 'name' => ['ar' => 'الجزائر', 'en' => 'Algeria']]);
        Country::create(['id' => 3, 'name' => ['ar' => 'العراق', 'en' => 'Iraq']]);
        Country::create(['id' => 4, 'name' => ['ar' => 'السودان', 'en' => 'Sudan']]);
        Country::create(['id' => 5, 'name' => ['ar' => 'أوكرانيا', 'en' => 'Ukraine']]);
    }
}
