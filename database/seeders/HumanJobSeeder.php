<?php

namespace Database\Seeders;

use App\Models\HumanJob;
use Illuminate\Database\Seeder;

class HumanJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        HumanJob::create(['id' => 1, 'name' => ['ar' => 'مزارع', 'en' => 'farmer']]);
        HumanJob::create(['id' => 2, 'name' => ['ar' => 'مهندس', 'en' => 'engineer']]);
        HumanJob::create(['id' => 3, 'name' => ['ar' => 'مهندس مساعد', 'en' => 'assistant engineer']]);
        HumanJob::create(['id' => 4, 'name' => ['ar' => 'عامل', 'en' => 'worker']]);
        HumanJob::create(['id' => 5, 'name' => ['ar' => 'طبيب', 'en' => 'doctor']]);
        HumanJob::create(['id' => 6, 'name' => ['ar' => 'طبيب بيطري', 'en' => 'vet']]);
        HumanJob::create(['id' => 7, 'name' => ['ar' => 'رجل أعمال', 'en' => 'business man']]);
    }
}
