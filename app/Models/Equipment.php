<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'total_quantity',
        'available_quantity',
        'condition',
        'asset_tag',
    ];

    public function reservations()
    {
        return $this->hasMany(EquipmentReservation::class);
    }

    public function activeReservations()
    {
        return $this->hasMany(EquipmentReservation::class)
            ->whereIn('status', ['reserved', 'borrowed']);
    }

    public function checkAvailability($from, $to, $quantity = 1, $excludeReservationId = null)
    {
        $query = $this->reservations()
            ->whereIn('status', ['reserved', 'borrowed'])
            ->where(function ($query) use ($from, $to) {
                $query->whereBetween('reserved_from', [$from, $to])
                    ->orWhereBetween('reserved_to', [$from, $to])
                    ->orWhere(function ($q) use ($from, $to) {
                        $q->where('reserved_from', '<=', $from)
                          ->where('reserved_to', '>=', $to);
                    });
            });

        // Exclude a specific reservation if provided (for updates)
        if ($excludeReservationId) {
            $query->where('id', '!=', $excludeReservationId);
        }

        $overlappingReservations = $query->sum('quantity');

        return ($this->available_quantity - $overlappingReservations) >= $quantity;
    }
}