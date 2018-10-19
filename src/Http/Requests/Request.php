<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Route;

class Request extends FormRequest
{
    protected $routePrefix = "";

    public function __construct() {
        $this->routePrefix = preg_replace('/^\//', '', Route::current()->getPrefix());

        parent::__construct();
    }

    public function authorize()
    {
        return true;
    }
}
