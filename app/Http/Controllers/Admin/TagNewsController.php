<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class TagNewsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
}
