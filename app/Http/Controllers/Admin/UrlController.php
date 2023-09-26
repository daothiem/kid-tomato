<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class UrlController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
}
