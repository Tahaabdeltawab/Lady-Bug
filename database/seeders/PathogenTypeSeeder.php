<?php

namespace Database\Seeders;

use App\Models\PathogenType;
use Illuminate\Database\Seeder;

class PathogenTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PathogenType::create(['id' => 1, 'name' => ['ar' => 'حشري', 'en' => 'Insectary']]);
        PathogenType::create(['id' => 2, 'name' => ['ar' => 'بكتيري', 'en' => 'Bacterial']]);
        PathogenType::create(['id' => 3, 'name' => ['ar' => 'فيروسي', 'en' => 'Viral']]);
        PathogenType::create(['id' => 4, 'name' => ['ar' => 'طفيلي', 'en' => 'Parasitic']]);
        PathogenType::create(['id' => 5, 'name' => ['ar' => 'فطري', 'en' => 'Fungal']]);
    }
}
