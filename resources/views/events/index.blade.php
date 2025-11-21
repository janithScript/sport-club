@extends('layouts.app')

@section('title', 'Events - Sports Club Management')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Sports Events</h1>
        @auth
            @if(auth()->user()->is_admin)
                <a href="/admin/events/create" class="btn btn-primary">Create Event</a>
            @endif
        @endauth
    </div>

    @if($events->count() > 0)
        <div class="row">
            @foreach($events as $event)
                <div class="col-md-4 mb-4">
                    <div class="card @if($event->start_at->isPast()) event-past @elseif($event->start_at->isToday()) event-ongoing @else event-upcoming @endif">
                        <div class="card-header">
                            {{ $event->title }}
                            @if($event->start_at->isPast())
                                <span class="badge badge-secondary float-right">Past</span>
                            @elseif($event->start_at->isToday())
                                <span class="badge badge-warning float-right">Today</span>
                            @else
                                <span class="badge badge-success float-right">Upcoming</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-2">
                                <strong>📅 Date:</strong> {{ $event->start_at->format('M d, Y') }}
                            </p>
                            <p class="text-muted mb-2">
                                <strong>⏰ Time:</strong> {{ $event->start_at->format('g:i A') }} - {{ $event->end_at->format('g:i A') }}
                            </p>
                            @if($event->location)
                                <p class="text-muted mb-2">
                                    <strong>📍 Location:</strong> {{ $event->location }}
                                </p>
                            @endif
                            <p>{{ Str::limit($event->description, 100) }}</p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                @if($event->capacity > 0)
                                    <span class="badge @if($event->available_spots > 0) badge-info @else badge-danger @endif">
                                        @if($event->available_spots > 0)
                                            {{ $event->available_spots }} spots left
                                        @else
                                            Full
                                        @endif
                                    </span>
                                @endif
                                
                                <small class="text-muted">
                                    {{ $event->confirmedRegistrations->count() }} registered
                                </small>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('events.show', $event) }}" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $events->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <h3>No events available</h3>
            <p class="text-muted">Check back later for upcoming events!</p>
        </div>
    @endif
</div>
@endsection