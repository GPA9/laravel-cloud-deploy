<?php

namespace App\Http\Controllers;

use App\Models\Character;

class DashboardController extends Controller
{
    public function index()
    {
        $characters = Character::all();
        return view('dashboard', compact('characters'));
    }
}
