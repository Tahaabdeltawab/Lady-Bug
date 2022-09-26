<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create([
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'type' => "app_admin",
            'mobile' => '01111111111',
            'human_job_id' => 1,
            'password' => bcrypt('11111111')
        ]);
        $admin->attachRole(config('myconfig.admin_role'));
        
        $user = User::create([
            'id' => 2,
            'name' => 'User',
            'email' => 'user@user.com',
            'type' => "app_user",
            'mobile' => '02222222222',
            'human_job_id' => 2,
            'password' => bcrypt('11111111')
        ]);
        $user->attachRole(config('myconfig.user_default_role'));
    }
}
