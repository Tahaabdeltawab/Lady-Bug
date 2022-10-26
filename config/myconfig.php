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

    'business_permissions' => [
        'create-activity',
        'edit-activity',
        'create-post',
        'edit-post',
        'create-product',
        'edit-product',
        'create-step',
        'edit-step',
        'create-goal',
        'edit-goal',
        'create-report',
        'edit-report',
        'edit-role',
        'edit-business',
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
        'image/webp',
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
