<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Equipment;
use App\Models\EventRegistration;
use App\Models\EquipmentReservation;
use App\Models\Message;
use App\Models\User;
use Carbon\Carbon;

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
            
        // Calculate dynamic statistics for trends
        // Get last month's data for comparison
        $lastMonth = Carbon::now()->subMonth();
        
        // Events comparison
        $lastMonthEvents = $user->eventRegistrations()
            ->whereHas('event', function ($query) use ($lastMonth) {
                $query->whereBetween('start_at', [$lastMonth->startOfMonth(), $lastMonth->endOfMonth()]);
            })
            ->count();
            
        $currentMonthEvents = $user->eventRegistrations()
            ->whereHas('event', function ($query) {
                $query->whereBetween('start_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
            })
            ->count();
            
        $eventsTrend = $this->calculateTrend($lastMonthEvents, $currentMonthEvents);
        
        // Equipment reservations comparison
        $lastMonthReservations = $user->equipmentReservations()
            ->whereBetween('created_at', [$lastMonth->startOfMonth(), $lastMonth->endOfMonth()])
            ->count();
            
        $currentMonthReservations = $user->equipmentReservations()
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->count();
            
        $reservationsTrend = $this->calculateTrend($lastMonthReservations, $currentMonthReservations);
        
        // Messages comparison
        $lastMonthMessages = $user->receivedMessages()
            ->whereBetween('created_at', [$lastMonth->startOfMonth(), $lastMonth->endOfMonth()])
            ->count();
            
        $currentMonthMessages = $user->receivedMessages()
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->count();
            
        $messagesTrend = $this->calculateTrend($lastMonthMessages, $currentMonthMessages);

        return view('dashboard.index', compact(
            'registeredEvents', 
            'recentReservations', 
            'unreadMessages',
            'eventsTrend',
            'reservationsTrend',
            'messagesTrend'
        ));
    }
    
    private function calculateTrend($previous, $current)
    {
        if ($previous == 0) {
            return $current > 0 ? ['percentage' => 100, 'direction' => 'up'] : ['percentage' => 0, 'direction' => 'neutral'];
        }
        
        $percentage = round((($current - $previous) / $previous) * 100);
        $direction = $percentage > 0 ? 'up' : ($percentage < 0 ? 'down' : 'neutral');
        
        return [
            'percentage' => abs($percentage),
            'direction' => $direction
        ];
    }
}