<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Auth;


use Exception;

class WebcamController extends Controller
{
    public function index()
    {
        return view('webcam_processing');
    }
}
