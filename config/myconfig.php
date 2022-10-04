<?php

return [

    /**
     *
     * ? farm_activity_types should be of ids 1 for crops, 2 for trees, 3 for home_plants, 4 for animals
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
    'business_roles' => [
        'business-admin',
        'business-editor',
        'business-supervisor',
        'business-consultant',
        'business-worker',
        'business-company',
    ],

    // used in sending the app roles and users api for the business owner to edit the roles
    'business_allowed_roles' => [
        'business-admin',
        'business-editor',
        'business-supervisor',
        'business-consultant',
        'business-worker',
        'business-company',
    ],

    'edit_business_allowed_roles' => [
        'business-admin',
        'business-editor',
    ],

    'show_business_allowed_roles' => [
        'business-admin',
        'business-editor',
        'business-supervisor',
        'business-consultant',
        'business-worker',
        'business-company',
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
