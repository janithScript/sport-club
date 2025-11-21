<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('confirmedRegistrations')
            ->orderBy('start_at')
            ->paginate(12);

        return view('events.index', compact('events'));
    }

    public function show(Event $event)
    {
        $event->load('confirmedRegistrations.user');
        
        $isRegistered = auth()->check() && 
            $event->isUserRegistered(auth()->id());

        return view('events.show', compact('event', 'isRegistered'));
    }
}