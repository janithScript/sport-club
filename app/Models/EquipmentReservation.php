<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id',
        'user_id',
        'quantity',
        'reserved_from',
        'reserved_to',
        'borrowed_at',
        'due_at',
        'returned_at',
        'status',
        'admin_note',
    ];

    protected $casts = [
        'reserved_from' => 'datetime',
        'reserved_to' => 'datetime',
        'borrowed_at' => 'datetime',
        'due_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isOverdue()
    {
        return $this->status === 'borrowed' && 
               $this->due_at && 
               $this->due_at->isPast();
    }
}