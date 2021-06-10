<?php

return [

    //farmed_types should be of ids 1 for crops, 2 for trees, 3 for home_plants, 4 for animals

    // there should be 4 records in the human_jobs table with english names identical to the coming array values
    // used in auth->register
    'companies_jobs' => [
        'pharma'    => 'Pharmaceutical Company',
        'chem'      => 'Chemical Company',
        'feed'      => 'Feed Company',
        'seed'      => 'Plant Nursery',
    ],

    'user_default_role' => 'app-user',

    'admin_role' => 'app-admin',

    'farm_roles' => [ // used not to get with app Roles of the dashboard
        'farm-admin',
        'farm-editor',
        'farm-supervisor'
    ],

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

    'post_statuses' => [
        'accepted',
        'reported',
        'blocked',
    ],
];
