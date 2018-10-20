<?php

/*
 * This file is part of the smallnews/laravel-shopcore.
 *
 * (c) smallnews <1371606921@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Route;

class Request extends FormRequest
{
    protected $routePrefix = '';

    public function __construct()
    {
        $this->routePrefix = preg_replace('/^\//', '', Route::current()->getPrefix());

        parent::__construct();
    }

    public function authorize()
    {
        return true;
    }
}
