<?php

return [

    /**
     * 
     * ? farmed_types should be of ids 1 for crops, 2 for trees, 3 for home_plants, 4 for animals
     * 
     */

    'farmed_type_stages' => [
        'pre_farming', 
        'germination', 
        'seedling_farming', 
        'growth', 
        'pre_flowering', 
        'flowering', 
        'maturity'
    ],

    /**
     * 
     * * Roles & Permissions
     * 
     */
    'user_default_role' => 'app-user',

    'admin_role' => 'app-admin',

    // used not to get with app Roles of the dashboard
    'farm_roles' => [ 
        'farm-admin',
        'farm-editor',
        'farm-supervisor'
    ],

    // used in sending the app roles and users api for the farm owner to edit the roles
    'farm_allowed_roles' => [ 
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

    /**
     * 
     * * Media
     * 
     */
    'video_mimes' => [
        'video/mp4',
        'video/*',
        'video/x-ms-wmv',
        'video/x-ms-asf', // for wmv
        'video/quicktime',
    ],

    'image_mimes' => [
        'image/png',
        'image/*',
        'image/jpeg',
        'image/svg+xml',
    ],

    /**
     * 
     * * Post Status
     * 
     */
    'post_statuses' => [
        'accepted',
        'reported',
        'blocked',
    ],
];
