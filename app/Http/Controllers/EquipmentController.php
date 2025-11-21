<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Equipment;

class EquipmentController extends Controller
{
    public function index()
    {
        $equipment = Equipment::with('activeReservations')
            ->orderBy('category')
            ->orderBy('name')
            ->paginate(12);

        return view('equipment.index', compact('equipment'));
    }

    public function show(Equipment $equipment)
    {
        $equipment->load('reservations.user');
        
        return view('equipment.show', compact('equipment'));
    }
}