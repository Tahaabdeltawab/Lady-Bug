<?php

namespace Database\Seeders;

use App\Models\TaskType;
use Illuminate\Database\Seeder;

class TaskTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TaskType::create(['id' => 1, 'name' => ['ar' => 'مكافحة', 'en' => 'Disease control']]);
        TaskType::create(['id' => 2, 'name' => ['ar' => 'ري', 'en' => 'Irrigation']]);
        TaskType::create(['id' => 3, 'name' => ['ar' => 'تسميد', 'en' => 'Fertilization']]);
        TaskType::create(['id' => 4, 'name' => ['ar' => 'عمليات حقلية', 'en' => 'Field work']]);
    }
}
