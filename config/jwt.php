<?php

return [
    'access_token_tl'=> env('JWT_ACC_TL', 60 * 15), // default 15 minutes
    'refresh_token_tl'=> env('JWT_REF_TL', 60 * 60 * 24 * 7), // default 7 days
    'access_secret' => env('JWT_ACCESS_SECRET', 'somesigningkey'),
    'refresh_secret' => env('JWT_REFRESH_SECRET', 'somesigningRefreshKey'),
];
