<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            [
                'name' => 'create-activity',
                'display_name' => 'create activity',
                'description' => 'إنشاء نشاط',
            ],
            [
                'name' => 'edit-activity',
                'display_name' => 'edit activity',
                'description' => 'تعديل نشاط',
            ],
            [
                'name' => 'create-post',
                'display_name' => 'create post',
                'description' => 'إنشاء منشور',
            ],
            [
                'name' => 'edit-post',
                'display_name' => 'edit post',
                'description' => 'تعديل منشور',
            ],
            [
                'name' => 'create-product',
                'display_name' => 'create product',
                'description' => 'إنشاء منتج',
            ],
            [
                'name' => 'edit-product',
                'display_name' => 'edit product',
                'description' => 'تعديل منتج',
            ],
            [
                'name' => 'create-step',
                'display_name' => 'create step',
                'description' => 'إنشاء تطور',
            ],
            [
                'name' => 'edit-step',
                'display_name' => 'edit step',
                'description' => 'تعديل تطور',
            ],
            [
                'name' => 'create-goal',
                'display_name' => 'create goal',
                'description' => 'إنشاء هدف',
            ],
            [
                'name' => 'edit-goal',
                'display_name' => 'edit goal',
                'description' => 'تعديل هدف',
            ],
            [
                'name' => 'create-report',
                'display_name' => 'create report',
                'description' => 'إنشاء تقرير',
            ],
            [
                'name' => 'edit-report',
                'display_name' => 'edit report',
                'description' => 'تعديل تقرير',
            ],
            [
                'name' => 'edit-role',
                'display_name' => 'edit role',
                'description' => 'تعديل الأدوار',
            ],
            [
                'name' => 'edit-business',
                'display_name' => 'edit business',
                'description' => 'تعديل العمل',
            ],
        ]);
    }
}
