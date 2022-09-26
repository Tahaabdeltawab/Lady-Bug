<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(FarmActivityTypeSeeder::class);
        $this->call(HumanJobSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PostTypeSeeder::class);
        $this->call(BusinessFieldSeeder::class);
        $this->call(WorkFieldSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(FarmedTypeStageSeeder::class);
        $this->call(FarmRelationsSeeder::class);
        $this->call(TaskTypeSeeder::class);
  }
}
