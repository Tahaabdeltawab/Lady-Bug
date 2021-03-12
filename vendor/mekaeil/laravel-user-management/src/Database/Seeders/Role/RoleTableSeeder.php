<?php

use Mekaeil\LaravelUserManagement\Seeders\Role\MasterRoleTableSeeder;
class RoleTableSeeder extends MasterRoleTableSeeder
{
    protected $roles = [
        [
            'name'          => "Admin",
            'title'         => "Administrator",
            'guard_name'    => "web",
            'description'   => "This role will assign to Administrator",
        ],
        [
            'name'          => "User",
            'title'         => "User",
            'guard_name'    => "web",
            'description'   => "This role will assign to user.",
        ],
        
    ];

    
}
