<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class apropos extends Controller
{
    public function index()
    {
        session_start();
        return view('apropos');
    }
}
