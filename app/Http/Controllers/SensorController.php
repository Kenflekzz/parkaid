<?php

namespace App\Http\Controllers;

class SensorController extends Controller
{
    /**
     * Show the Park Aid dashboard with static dummy data.
     * No DB, no broadcasting — UI preview only.
     */
    public function index()
    {
        return view('index');
    }
}