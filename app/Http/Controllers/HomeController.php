<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $coaches = \App\Models\Coach::where('is_available', true)->get();
        $courts = \App\Models\Court::where('status', 'available')->get();
        return view('home', compact('coaches', 'courts'));
    }
}
