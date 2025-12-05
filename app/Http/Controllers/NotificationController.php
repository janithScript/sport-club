<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['notifications' => [], 'count' => 0]);
        }

        $user = auth()->user();
        
        // Get upcoming events (within 24 hours)
        $upcomingEvents = $user->getUpcomingEvents();
        
        // Get equipment reservations due within 24 hours
        $dueReservations = $user->getDueEquipmentReservations();
        
        $notifications = [];
        
        // Process event notifications
        foreach ($upcomingEvents as $registration) {
            $event = $registration->event;
            $startTime = Carbon::parse($event->start_at);
            $hoursUntilStart = now()->diffInHours($startTime, false);
            
            $notifications[] = [
                'id' => 'event_' . $event->id,
                'type' => 'event',
                'event_id' => $event->id,
                'message' => "Event '{$event->title}' starts in " . $this->formatTimeRemaining($hoursUntilStart),
                'time_remaining' => $startTime->diffForHumans(),
            ];
        }
        
        // Process equipment notifications
        foreach ($dueReservations as $reservation) {
            $equipment = $reservation->equipment;
            $dueTime = Carbon::parse($reservation->due_at);
            $hoursUntilDue = now()->diffInHours($dueTime, false);
            
            $notifications[] = [
                'id' => 'equipment_' . $equipment->id,
                'type' => 'equipment',
                'message' => "You have {$reservation->quantity} '{$equipment->name}' to return in " . $this->formatTimeRemaining($hoursUntilDue),
                'time_remaining' => $dueTime->diffForHumans(),
            ];
        }
        
        return response()->json([
            'notifications' => $notifications,
            'count' => count($notifications)
        ]);
    }
    
    public function count(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['count' => 0]);
        }

        $user = auth()->user();
        $count = $user->getNotificationCount();
        
        return response()->json(['count' => $count]);
    }
    
    private function formatTimeRemaining($hours)
    {
        if ($hours < 1) {
            return 'less than an hour';
        } elseif ($hours == 1) {
            return '1 hour';
        } else {
            return "{$hours} hours";
        }
    }
}