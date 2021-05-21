<?php

return [

    'user_default_role' => 'app-user',

    'admin_role' => 'app-admin',

    'farm_allowed_roles' => [ // used in sending the app roles and users api for the farm owner to edit the roles
        // 'farm-admin', // this role is only for the farm creator
        'farm-editor',
        'farm-supervisor'
    ],

    'edit_farm_allowed_roles' => [
        'farm-admin',
        'farm-editor',
    ],

    'show_farm_allowed_roles' => [
        'farm-admin',
        'farm-editor',
        'farm-supervisor'
    ],


    'video_mimes' => [
        'video/mp4',
        'video/x-ms-wmv',
        'video/x-ms-asf', // for wmv
        'video/quicktime',
    ],

    'image_mimes' => [
        'image/png',
        'image/jpeg',
        'image/svg+xml',
    ],

];
