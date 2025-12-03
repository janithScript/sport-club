@extends('layouts.app')

@section('title', 'Home - Sports Club Management')

@section('content')
<!-- Hero Section -->
<div class="hero">
    <div class="container">
        <h1>Welcome to Sports Club</h1>
        <p>Join our vibrant community and participate in exciting sports events!</p>
        <div class="hero-buttons">
            @guest
                <a href="{{ route('register') }}" class="btn btn-success">Join Now</a>
                <a href="{{ route('events.index') }}" class="btn btn-secondary">View Events</a>
            @else
                <a href="{{ route('dashboard.index') }}" class="btn btn-success">My Dashboard</a>
                <a href="{{ route('events.index') }}" class="btn btn-secondary">Explore Events</a>
            @endguest
        </div>
    </div>
</div>

<!-- Statistics Section -->
<div class="stats">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="stat-item">
                    <span class="stat-number">{{ \App\Models\Event::count() }}</span>
                    <span class="stat-label">Total Events</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-item">
                    <span class="stat-number">{{ \App\Models\User::where('is_admin', false)->count() }}</span>
                    <span class="stat-label">Active Members</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-item">
                    <span class="stat-number">{{ \App\Models\Equipment::sum('total_quantity') }}</span>
                    <span class="stat-label">Equipment Items</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Events -->
@if($upcomingEvents->count() > 0)
<div class="container mb-5">
    <h2 class="text-center mb-4">Upcoming Events</h2>
    <div class="row">
        @foreach($upcomingEvents as $event)
            <div class="col-md-6 mb-4">
                <div class="card event-upcoming">
                    <div class="card-header">
                        {{ $event->title }}
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-2">
                            <strong>Date:</strong> {{ $event->start_at->format('M d, Y') }} at {{ $event->start_at->format('g:i A') }}
                        </p>
                        @if($event->location)
                            <p class="text-muted mb-2">
                                <strong>Location:</strong> {{ $event->location }}
                            </p>
                        @endif
                        <p>{{ Str::limit($event->description, 100) }}</p>
                        
                        @if($event->capacity > 0)
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge badge-info">
                                    {{ $event->available_spots ?? 'Full' }} spots available
                                </span>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('events.show', $event) }}" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <div class="text-center">
        <a href="{{ route('events.index') }}" class="btn btn-primary">View All Events</a>
    </div>
</div>
@endif

<!-- Features Section -->
<div class="container mb-5">
    <h2 class="text-center mb-4">Why Choose Our Sports Club?</h2>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fas fa-trophy me-2"></i>Event Management
                </div>
                <div class="card-body">
                    <h3>Event Management</h3>
                    <p>Discover and register for exciting sports events. Stay updated with schedules and never miss an opportunity to participate.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fas fa-football-ball me-2"></i>Equipment Booking
                </div>
                <div class="card-body">
                    <h3>Equipment Booking</h3>
                    <p>Reserve sports equipment for your activities. Check availability and book equipment for your preferred time slots.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <i class="fas fa-comments me-2"></i>Communication
                </div>
                <div class="card-body">
                    <h3>Communication</h3>
                    <p>Stay connected with club administrators and other members through our integrated messaging system.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection