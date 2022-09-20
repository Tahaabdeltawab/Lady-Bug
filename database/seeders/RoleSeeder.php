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
                'name' => 'business-admin',
                'display_name' => 'Business Admin',
                'description' =>'مدير مزرعة',
            ],
            [
                'name' => 'business-editor',
                'display_name' => 'Business Editor',
                'description' =>'محرر مزرعة',
            ],
            [
                'name' => 'business-supervisor',
                'display_name' => 'Business Supervisor',
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
