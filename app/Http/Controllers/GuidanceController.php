<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuidanceController extends Controller
{
    public function index()
    {
        return view('panduan.index'); // Sesuaikan dengan nama file blade panduan Anda
    }
}