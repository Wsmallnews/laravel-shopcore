<?php

/*
 * This file is part of the smallnews/laravel-shopcore.
 *
 * (c) smallnews <1371606921@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | users auth driver
    |--------------------------------------------------------------------------
    |
    | Admin is the driver of management
    | Desk is the driver of the user
    |
    */
    'guard' => [
        'admin' => 'admin',
        'desk' => 'web',
    ],
];
