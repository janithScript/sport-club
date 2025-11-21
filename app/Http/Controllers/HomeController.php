<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class HomeController extends Controller
{
    public function index()
    {
        $upcomingEvents = Event::where('start_at', '>', now())
            ->orderBy('start_at')
            ->take(5)
            ->get();

        return view('home', compact('upcomingEvents'));
    }
}