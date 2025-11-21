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
}