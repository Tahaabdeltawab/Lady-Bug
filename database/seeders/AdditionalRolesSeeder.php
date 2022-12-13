<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdditionalRolesSeeder extends Seeder
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
                'id' => 6,
                'name' => 'business-consultant',
                'display_name' => 'Business consultant',
                'description' =>'استشاري',
            ],
            [
                'id' => 7,
                'name' => 'business-worker',
                'display_name' => 'Business Worker',
                'description' =>'عامل',
            ],
            /* [
                'id' => 8,
                'name' => 'business-company',
                'display_name' => 'Company',
                'description' =>'شركة',
            ], */
        ]);
    }
}
