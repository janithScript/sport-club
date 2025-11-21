@extends('layouts.app')

@section('title', $event->title . ' - Sports Club Management')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">{{ $event->title }}</h2>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>📅 Start Date:</strong> {{ $event->start_at->format('M d, Y g:i A') }}</p>
                            <p><strong>📅 End Date:</strong> {{ $event->end_at->format('M d, Y g:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            @if($event->location)
                                <p><strong>📍 Location:</strong> {{ $event->location }}</p>
                            @endif
                            @if($event->capacity > 0)
                                <p><strong>👥 Capacity:</strong> {{ $event->capacity }} people</p>
                                <p><strong>✅ Available Spots:</strong> 
                                    <span class="badge @if($event->available_spots > 0) badge-success @else badge-danger @endif">
                                        {{ $event->available_spots ?? 0 }}
                                    </span>
                                </p>
                            @endif
                        </div>
                    </div>

                    @if($event->description)
                        <div class="mb-4">
                            <h5>Description</h5>
                            <p>{{ $event->description }}</p>
                        </div>
                    @endif

                    @auth
                        @if(!$event->start_at->isPast())
                            <div class="mt-4">
                                @if(!$isRegistered)
                                    @if(!$event->isFull())
                                        <form action="{{ route('events.register', $event) }}" method="POST" 
                                              x-data="{ loading: false }" 
                                              @submit="loading = true">
                                            @csrf
                                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                                            <button type="submit" class="btn btn-success" 
                                                    :disabled="loading"
                                                    :class="{ 'loading': loading }">
                                                <span x-show="loading" class="spinner"></span>
                                                Register for Event
                                            </button>
                                        </form>
                                    @else
                                        <p class="text-danger">This event is full.</p>
                                    @endif
                                @else
                                    <div class="alert alert-success">
                                        ✅ You are registered for this event!
                                    </div>
                                    <form action="{{ route('events.unregister', $event) }}" method="POST" 
                                          x-data="{ loading: false }" 
                                          @submit="loading = true"
                                          onsubmit="return confirm('Are you sure you want to cancel your registration?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" 
                                                :disabled="loading"
                                                :class="{ 'loading': loading }">
                                            <span x-show="loading" class="spinner"></span>
                                            Cancel Registration
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info">
                            <a href="{{ route('login') }}">Login</a> or <a href="{{ route('register') }}">register</a> to join this event.
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Event Statistics -->
            <div class="card mb-4">
                <div class="card-header">
                    Event Statistics
                </div>
                <div class="card-body">
                    <div class="stat-item">
                        <span class="stat-number text-primary">{{ $event->confirmedRegistrations->count() }}</span>
                        <span class="stat-label">Registered Members</span>
                    </div>
                    @if($event->capacity > 0)
                        <div class="stat-item mt-3">
                            <span class="stat-number text-success">{{ $event->available_spots ?? 0 }}</span>
                            <span class="stat-label">Available Spots</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Registered Members -->
            @if($event->confirmedRegistrations->count() > 0)
                <div class="card">
                    <div class="card-header">
                        Registered Members
                    </div>
                    <div class="card-body">
                        @foreach($event->confirmedRegistrations->take(10) as $registration)
                            <div class="d-flex align-items-center mb-2">
                                <div class="mr-3">👤</div>
                                <div>
                                    <strong>{{ $registration->user->name }}</strong><br>
                                    <small class="text-muted">Registered {{ $registration->registered_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @endforeach
                        
                        @if($event->confirmedRegistrations->count() > 10)
                            <small class="text-muted">
                                And {{ $event->confirmedRegistrations->count() - 10 }} more members...
                            </small>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection