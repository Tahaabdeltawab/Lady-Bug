<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'name' => 'farm-admin',
                'display_name' => 'Farm Admin',
                'description' =>'مدير مزرعة',
            ],
            [
                'name' => 'farm-editor',
                'display_name' => 'Farm Editor',
                'description' =>'محرر مزرعة',
            ],
            [
                'name' => 'farm-supervisor',
                'display_name' => 'Farm Supervisor',
                'description' =>'مشرف مزرعة',
            ],
            [
                'name' => 'app-user',
                'display_name' => 'App User',
                'description' =>'مستخدم',
            ],
            [
                'name' => 'app-admin',
                'display_name' => 'App Admin',
                'description' =>'مدير',
            ],
        ]);
    }
}
