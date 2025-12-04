<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Models\EquipmentReservation;

class EquipmentReservationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'quantity' => 'required|integer|min:1',
            'reserved_from' => 'required|date|after:now',
            'reserved_to' => 'required|date|after:reserved_from',
        ]);

        $equipment = Equipment::findOrFail($request->equipment_id);

        if (!$equipment->checkAvailability(
            $request->reserved_from,
            $request->reserved_to,
            $request->quantity
        )) {
            return redirect()->back()->with('error', 'Insufficient equipment available for the requested time period!');
        }

        EquipmentReservation::create([
            'equipment_id' => $request->equipment_id,
            'user_id' => auth()->id(),
            'quantity' => $request->quantity,
            'reserved_from' => $request->reserved_from,
            'reserved_to' => $request->reserved_to,
            'status' => 'reserved',
        ]);

        return redirect()->back()->with('success', 'Equipment reserved successfully! Please wait for admin approval.');
    }

    public function edit(EquipmentReservation $reservation)
    {
        // Ensure the user can only edit their own reservations
        if ($reservation->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Only allow editing if the reservation is in 'reserved' status
        if ($reservation->status !== 'reserved') {
            return redirect()->back()->with('error', 'You can only edit reservations that are in reserved status.');
        }

        return view('equipment.edit_reservation', compact('reservation'));
    }

    public function update(Request $request, EquipmentReservation $reservation)
    {
        // Ensure the user can only update their own reservations
        if ($reservation->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Only allow updating if the reservation is in 'reserved' status
        if ($reservation->status !== 'reserved') {
            return redirect()->back()->with('error', 'You can only update reservations that are in reserved status.');
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
            'reserved_from' => 'required|date|after:now',
            'reserved_to' => 'required|date|after:reserved_from',
        ]);

        // Check if the equipment is still available for the new dates
        $equipment = $reservation->equipment;
        if (!$equipment->checkAvailability(
            $request->reserved_from,
            $request->reserved_to,
            $request->quantity,
            $reservation->id // Exclude current reservation from availability check
        )) {
            return redirect()->back()->with('error', 'Insufficient equipment available for the requested time period!');
        }

        $reservation->update([
            'quantity' => $request->quantity,
            'reserved_from' => $request->reserved_from,
            'reserved_to' => $request->reserved_to,
        ]);

        return redirect()->route('dashboard.index')->with('success', 'Reservation updated successfully!');
    }

    public function destroy(EquipmentReservation $reservation)
    {
        // Ensure the user can only delete their own reservations
        if ($reservation->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Only allow deleting if the reservation is in 'reserved' status
        if ($reservation->status !== 'reserved') {
            return redirect()->back()->with('error', 'You can only delete reservations that are in reserved status.');
        }

        $reservation->delete();

        return redirect()->route('dashboard.index')->with('success', 'Reservation deleted successfully!');
    }
}