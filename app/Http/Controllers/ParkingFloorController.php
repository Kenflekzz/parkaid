<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ParkingFloorController extends Controller
{
    /**
     * Display the parking floors page.
     */
    public function index()
    {
        return view('parking-floors');
    }
}