<?php

namespace Database\Seeders;

use App\Models\BusinessField;
use Illuminate\Database\Seeder;

class BusinessFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BusinessField::create(['id' => 1, 'name' => ['ar' => 'مزرعة', 'en' => 'Farm']]);
        BusinessField::create(['id' => 2, 'name' => ['ar' => 'تجارة المبيدات', 'en' => 'Insecticides Trade']]);
        BusinessField::create(['id' => 3, 'name' => ['ar' => 'تجارة الأسمدة', 'en' => 'Fertilizers Trade']]);
        BusinessField::create(['id' => 4, 'name' => ['ar' => 'تجارة الأعلاف', 'en' => 'Animal Fodders Trade']]);
        BusinessField::create(['id' => 5, 'name' => ['ar' => 'تجارة الأدوية البيطرية', 'en' => 'Vet Medicines Trade']]);
    }
}
