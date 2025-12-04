<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'is_admin',
        'profile_image',
        'about_me',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_admin' => 'boolean',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        // Debugging: Log the panel ID and user admin status
        Log::info('Checking panel access', [
            'panel_id' => $panel->getId(),
            'user_is_admin' => $this->is_admin,
            'user_id' => $this->id
        ]);
        
        // Check if this is the admin panel and user is an admin
        if ($panel->getId() === 'admin') {
            return $this->is_admin ?? false;
        }
        
        // For other panels, allow access
        return true;
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function equipmentReservations()
    {
        return $this->hasMany(EquipmentReservation::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
}