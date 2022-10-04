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
                'id' => 1,
                'name' => 'business-admin',
                'display_name' => 'Business Admin',
                'description' =>'مدير مزرعة',
            ],
            [
                'id' => 2,
                'name' => 'business-editor',
                'display_name' => 'Business Editor',
                'description' =>'محرر مزرعة',
            ],
            [
                'id' => 3,
                'name' => 'business-supervisor',
                'display_name' => 'Business Supervisor',
                'description' =>'مشرف مزرعة',
            ],
            [
                'id' => 4,
                'name' => 'app-user',
                'display_name' => 'App User',
                'description' =>'مستخدم',
            ],
            [
                'id' => 5,
                'name' => 'app-admin',
                'display_name' => 'App Admin',
                'description' =>'مدير',
            ],
        ]);
    }
}
