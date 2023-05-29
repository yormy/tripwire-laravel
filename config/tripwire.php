<?php

use Mexion\BedrockUsers\Models\Member;
use Mexion\BedrockUsers\Models\Admin;

return [
    /*
    |--------------------------------------------------------------------------
    | Checksums
    |--------------------------------------------------------------------------
    | Some checksums are posted by the frontend and validated on the backend.
    | These header values of the config need to match with the settings of your frontend
    |
    */
    'checksums' => [
        'posted' => 'X-Checksum',   // need to match with your frontend
        'timestamp' => 'X-sand',    // need to match with your frontend
        'serverside_calculated' => 'x-checksum-serverside',
    ],


    /*
    |--------------------------------------------------------------------------
    | Honeypots
    |--------------------------------------------------------------------------
    |
    |
    */
    'honeypots' => [
        'must_be_missing_or_false' => [
            'isAdmin',
            'debug',
            'logged_in',
            'is_admin',
            'is_debug',
            'show_log',
            'skip_encryption',
        ]
    ],
];
