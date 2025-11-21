<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventRegistration;

class EventRegistrationController extends Controller
{
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        if ($event->isFull()) {
            return redirect()->back()->with('error', 'Event is full!');
        }

        if ($event->isUserRegistered(auth()->id())) {
            return redirect()->back()->with('error', 'You are already registered for this event!');
        }

        EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => auth()->id(),
            'status' => 'confirmed',
            'registered_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Successfully registered for the event!');
    }

    public function destroy(Event $event)
    {
        $registration = EventRegistration::where([
            'event_id' => $event->id,
            'user_id' => auth()->id(),
        ])->first();

        if ($registration) {
            $registration->delete();
            return redirect()->back()->with('success', 'Registration cancelled successfully!');
        }

        return redirect()->back()->with('error', 'Registration not found!');
    }
}