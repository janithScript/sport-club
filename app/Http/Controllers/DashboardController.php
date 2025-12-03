<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Equipment;
use App\Models\EventRegistration;
use App\Models\EquipmentReservation;
use App\Models\Message;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Get upcoming events the user has registered for
        $registeredEvents = $user->eventRegistrations()
            ->with('event')
            ->whereHas('event', function ($query) {
                $query->where('start_at', '>', now());
            })
            ->get()
            ->pluck('event');
            
        // Get recent equipment reservations
        $recentReservations = $user->equipmentReservations()
            ->with('equipment')
            ->latest()
            ->take(5)
            ->get();
            
        // Get unread messages
        $unreadMessages = $user->receivedMessages()
            ->whereNull('read_at')
            ->count();

        return view('dashboard.index', compact('registeredEvents', 'recentReservations', 'unreadMessages'));
    }
}