<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Event;
use App\Models\Equipment;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        

        // Create regular users
        User::create([
            'name' => 'John Doe',
            'email' => 'john@sportsclub.local',
            'password' => Hash::make('password'),
            'phone' => '123-456-7890',
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@sportsclub.local',
            'password' => Hash::make('password'),
            'phone' => '098-765-4321',
        ]);

        // Create events
        Event::create([
            'title' => 'Football Practice',
            'description' => 'Weekly football practice session for all skill levels.',
            'location' => 'Main Sports Field',
            'start_at' => now()->addDays(3)->setTime(16, 0),
            'end_at' => now()->addDays(3)->setTime(18, 0),
            'capacity' => 22,
            'created_by' => 1,
        ]);

        Event::create([
            'title' => 'Basketball Tournament',
            'description' => 'Annual basketball tournament - registration required.',
            'location' => 'Indoor Basketball Court',
            'start_at' => now()->addWeeks(2)->setTime(9, 0),
            'end_at' => now()->addWeeks(2)->setTime(17, 0),
            'capacity' => 16,
            'created_by' => 1,
        ]);

        Event::create([
            'title' => 'Swimming Competition',
            'description' => 'Inter-club swimming competition.',
            'location' => 'Aquatic Center',
            'start_at' => now()->addMonth()->setTime(10, 0),
            'end_at' => now()->addMonth()->setTime(16, 0),
            'capacity' => 50,
            'created_by' => 1,
        ]);

        // Create equipment
        Equipment::create([
            'name' => 'Football',
            'category' => 'Ball',
            'total_quantity' => 10,
            'available_quantity' => 10,
            'condition' => 'excellent',
            'asset_tag' => 'BALL-001',
        ]);

        Equipment::create([
            'name' => 'Basketball',
            'category' => 'Ball',
            'total_quantity' => 8,
            'available_quantity' => 8,
            'condition' => 'good',
            'asset_tag' => 'BALL-002',
        ]);

        Equipment::create([
            'name' => 'Tennis Racket',
            'category' => 'Racket',
            'total_quantity' => 6,
            'available_quantity' => 6,
            'condition' => 'excellent',
            'asset_tag' => 'RACKET-001',
        ]);

        Equipment::create([
            'name' => 'Volleyball Net',
            'category' => 'Net',
            'total_quantity' => 2,
            'available_quantity' => 2,
            'condition' => 'good',
            'asset_tag' => 'NET-001',
        ]);

        Equipment::create([
            'name' => 'Swimming Goggles',
            'category' => 'Swimming',
            'total_quantity' => 15,
            'available_quantity' => 15,
            'condition' => 'excellent',
            'asset_tag' => 'SWIM-001',
        ]);
    }
}