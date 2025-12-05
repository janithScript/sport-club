@extends('layouts.app')

@section('title', 'Dashboard - Sports Club Management')

@section('head')
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/TextPlugin.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endsection

@section('content')
<!-- Loading Overlay -->
<div id="loadingOverlay" class="loading-overlay">
    <div class="loading-spinner">
        <div class="spinner-ring"></div>
        <div class="loading-text">Loading Dashboard...</div>
    </div>
</div>

<!-- Dashboard Container -->
<div class="dashboard-container" id="dashboardContainer">
    <!-- Performance Stats Bar -->
    <div class="performance-bar">
        <div class="performance-metrics">
            <div class="metric">
                <span class="metric-label">System Status</span>
                <span class="metric-value status-indicator online">Online</span>
            </div>
            <div class="metric">
                <span class="metric-label">Last Updated</span>
                <span class="metric-value" id="lastUpdated">Just now</span>
            </div>
            <div class="metric">
                <span class="metric-label">Response Time</span>
                <span class="metric-value">< 100ms</span>
            </div>
        </div>
        <div class="refresh-controls">
            <button class="refresh-btn" id="refreshData">
                <i class="fas fa-sync-alt"></i>
                <span>Refresh</span>
            </button>
            <button class="theme-toggle" id="themeToggle">
                <i class="fas fa-moon"></i>
            </button>
        </div>
    </div>

    <!-- Welcome Section -->
    <div class="welcome-section">
        <div class="welcome-background">
            <div class="animated-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
            </div>
        </div>
        <div class="welcome-content">
            <div class="welcome-text">
                <h1 class="welcome-title">
                    Welcome back, 
                    <span class="name-highlight" data-text="{{ Auth::user()->name }}">{{ Auth::user()->name }}</span>!
                </h1>
                <p class="welcome-subtitle typing-text" data-text="Here's what's happening with your sports club activities today."></p>
                <div class="welcome-stats">
                    <div class="quick-stat">
                        <i class="fas fa-fire"></i>
                        <span>Active Member</span>
                    </div>
                    <div class="quick-stat">
                        <i class="fas fa-trophy"></i>
                        <span>{{ $registeredEvents->count() }} Events</span>
                    </div>
                </div>
            </div>
            <div class="welcome-visual">
                <div class="floating-icon-container">
                    <div class="floating-icon">
                        <i class="fas fa-running"></i>
                    </div>
                    <div class="icon-glow"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card modern-card" data-color="primary" data-value="{{ $registeredEvents->count() }}">
            <div class="card-background"></div>
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-calendar"></i>
                </div>
                <div class="stat-menu">
                    <i class="fas fa-ellipsis-v"></i>
                </div>
            </div>
            <div class="stat-content">
                <span class="stat-label">Upcoming Events</span>
                <span class="stat-value counter" data-target="{{ $registeredEvents->count() }}">{{ $registeredEvents->count() }}</span>
            </div>
            
        </div>

        <div class="stat-card modern-card" data-color="success" data-value="{{ $recentReservations->count() }}">
            <div class="card-background"></div>
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-dumbbell"></i>
                </div>
                <div class="stat-menu">
                    <i class="fas fa-ellipsis-v"></i>
                </div>
            </div>
            <div class="stat-content">
                <span class="stat-label">Equipment Reservations</span>
                <span class="stat-value counter" data-target="{{ $recentReservations->count() }}">{{ $recentReservations->count() }}</span>
            </div>
            
        </div>

        <div class="stat-card modern-card" data-color="warning" data-value="{{ $unreadMessages }}">
            <div class="card-background"></div>
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-envelope"></i>
                    @if($unreadMessages > 0)
                        <div class="notification-badge">{{ $unreadMessages }}</div>
                    @endif
                </div>
                <div class="stat-menu">
                    <i class="fas fa-ellipsis-v"></i>
                </div>
            </div>
            <div class="stat-content">
                <span class="stat-label">Unread Messages</span>
                <span class="stat-value counter" data-target="{{ $unreadMessages }}">{{ $unreadMessages }}</span>
            </div>
           
        </div>

        <div class="stat-card modern-card" data-color="info">
            <div class="card-background"></div>
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="stat-menu">
                    <i class="fas fa-ellipsis-v"></i>
                </div>
            </div>
            <div class="stat-content">
                <span class="stat-label">Member Since</span>
                <span class="stat-value member-since">{{ Auth::user()->created_at->format('M Y') }}</span>
            </div>
            <div class="stat-footer">
                <div class="stat-trend">
                    <span class="trend-indicator positive">
                        <i class="fas fa-heart"></i> Active
                    </span>
                    <span class="trend-text">Premium Member</span>
                </div>
                <div class="member-progress">
                    <div class="progress-bar">
                        <div class="progress-fill" data-progress="75"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content with Enhanced Layout -->
    <div class="main-content">
        <div class="main-content-left">
            <!-- Events Section -->
            <div class="content-card events-card priority-section glass-effect">
                <div class="card-header enhanced-header">
                    <div class="header-content">
                        <h3 class="card-title">
                            <i class="fas fa-calendar-alt title-icon"></i>
                            Your Upcoming Events
                        </h3>
                        <p class="card-subtitle">Stay on top of your schedule</p>
                    </div>
                    <div class="header-actions">
                        <button class="filter-btn">
                            <i class="fas fa-filter"></i>
                        </button>
                        <a href="{{ route('events.index') }}" class="btn-modern primary pulse">
                            <span>View All Events</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="card-content">
                    @if($registeredEvents->count() > 0)
                        <div class="events-list enhanced-list">
                            @foreach($registeredEvents as $index => $event)
                                <div class="event-item advanced-item" data-index="{{ $index }}">
                                    <div class="event-indicator"></div>
                                    <div class="event-date modern-date">
                                        <span class="date-day">{{ $event->start_at->format('d') }}</span>
                                        <span class="date-month">{{ $event->start_at->format('M') }}</span>
                                        <span class="date-year">{{ $event->start_at->format('Y') }}</span>
                                    </div>
                                    <div class="event-details enhanced-details">
                                        <div class="event-header">
                                            <h4 class="event-title">{{ $event->title }}</h4>
                                            <div class="event-priority">
                                                <span class="priority-badge high">High Priority</span>
                                            </div>
                                        </div>
                                        <div class="event-meta">
                                            <p class="event-time">
                                                <i class="fas fa-clock"></i>
                                                {{ $event->start_at->format('g:i A') }}
                                            </p>
                                            @if($event->location)
                                                <p class="event-location">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    {{ $event->location }}
                                                </p>
                                            @endif
                                        </div>
                                        <p class="event-description">{{ Str::limit($event->description, 100) }}</p>
                                        
                                    </div>
                                    <div class="event-actions enhanced-actions">
                                        <button class="action-btn quick-action" title="Quick View">
                                            
                                        </button>
                                        <a href="{{ route('events.show', $event) }}" class="btn-modern outline">
                                            <span>View Details</span>
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state enhanced-empty">
                            <div class="empty-animation">
                                <div class="empty-icon animated">
                                    <i class="fas fa-calendar-times"></i>
                                </div>
                                <div class="empty-particles">
                                    <div class="particle"></div>
                                    <div class="particle"></div>
                                    <div class="particle"></div>
                                </div>
                            </div>
                            <h4>No Events Registered</h4>
                            <p>Discover exciting sports events and activities waiting for you!</p>
                            <a href="{{ route('events.index') }}" class="btn-modern primary glow">
                                <span>Explore Events</span>
                                <i class="fas fa-search"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions with Advanced Styling -->
            <div class="content-card actions-card priority-section glass-effect">
                <div class="card-header enhanced-header">
                    <div class="header-content">
                        <h3 class="card-title">
                            <i class="fas fa-bolt title-icon"></i>
                            Quick Actions
                        </h3>
                        <p class="card-subtitle">Frequently used features</p>
                    </div>
                </div>
                <div class="card-content">
                    <div class="actions-grid enhanced-grid">
                        <a href="{{ route('events.index') }}" class="action-btn advanced-action" data-color="primary">
                            <div class="action-background"></div>
                            <div class="action-icon">
                                <i class="fas fa-calendar-plus"></i>
                            </div>
                            <div class="action-content">
                                <span class="action-title">Browse Events</span>
                                <span class="action-desc">Find new activities</span>
                            </div>
                            <div class="action-arrow">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </a>
                        
                        <a href="{{ route('equipment.index') }}" class="action-btn advanced-action" data-color="success">
                            <div class="action-background"></div>
                            <div class="action-icon">
                                <i class="fas fa-dumbbell"></i>
                            </div>
                            <div class="action-content">
                                <span class="action-title">Reserve Equipment</span>
                                <span class="action-desc">Book your gear</span>
                            </div>
                            <div class="action-arrow">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </a>
                        
                        <a href="{{ route('messages.index') }}" class="action-btn advanced-action" data-color="info">
                            <div class="action-background"></div>
                            <div class="action-icon">
                                <i class="fas fa-envelope"></i>
                                @if($unreadMessages > 0)
                                    <div class="notification-dot"></div>
                                @endif
                            </div>
                            <div class="action-content">
                                <span class="action-title">Messages</span>
                                <span class="action-desc">Check updates</span>
                            </div>
                            <div class="action-arrow">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </a>
                        
                        <a href="{{ route('profile.show') }}" class="action-btn advanced-action" data-color="warning">
                            <div class="action-background"></div>
                            <div class="action-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="action-content">
                                <span class="action-title">Profile</span>
                                <span class="action-desc">Edit settings</span>
                            </div>
                            <div class="action-arrow">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Equipment Reservations with Timeline -->
            <div class="content-card reservations-card glass-effect">
                <div class="card-header enhanced-header">
                    <div class="header-content">
                        <h3 class="card-title">
                            <i class="fas fa-history title-icon"></i>
                            Recent Activity
                        </h3>
                        <p class="card-subtitle">Your latest reservations</p>
                    </div>
                </div>
                <div class="card-content">
                    @if($recentReservations->count() > 0)
                        <div class="reservations-timeline">
                            @foreach($recentReservations as $index => $reservation)
                                <div class="timeline-item" data-index="{{ $index }}">
                                    <div class="timeline-connector"></div>
                                    <div class="timeline-marker">
                                        <i class="fas fa-dumbbell"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="reservation-header">
                                            <h5 class="reservation-title">{{ $reservation->equipment->name }}</h5>
                                            <span class="reservation-status active">Active</span>
                                        </div>
                                        <p class="reservation-date">
                                            <i class="fas fa-calendar"></i>
                                            {{ $reservation->reserved_from ? $reservation->reserved_from->format('M d, Y') : 'N/A' }}
                                        </p>
                                        <div class="reservation-progress">
                                            <div class="progress-bar small">
                                                <div class="progress-fill" data-progress="60"></div>
                                            </div>
                                            <span class="progress-text">2 days remaining</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state mini enhanced-empty">
                            <div class="empty-icon">
                                <i class="fas fa-dumbbell"></i>
                            </div>
                            <h5>No Recent Activity</h5>
                            <p>Start by reserving some equipment</p>
                            <a href="{{ route('equipment.index') }}" class="btn-modern outline small">
                                <span>Browse Equipment</span>
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="main-content-right">
            <!-- Equipment Reservations Section -->
            <div class="content-card reservations-card priority-section glass-effect">
                <div class="card-header enhanced-header">
                    <div class="header-content">
                        <h3 class="card-title">
                            <i class="fas fa-dumbbell title-icon"></i>
                            Your Equipment Reservations
                        </h3>
                        <p class="card-subtitle">Manage your reserved equipment</p>
                    </div>
                    <div class="header-actions">
                        <a href="{{ route('equipment.index') }}" class="btn-modern primary pulse">
                            <span>Reserve More</span>
                            <i class="fas fa-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="card-content">
                    @if($userReservations->count() > 0)
                        <div class="reservations-list enhanced-list">
                            @foreach($userReservations as $reservation)
                                <div class="reservation-item advanced-item" data-reservation-id="{{ $reservation->id }}">
                                    <div class="reservation-indicator {{ $reservation->status }}"></div>
                                    <div class="reservation-details enhanced-details">
                                        <div class="reservation-header">
                                            <h4 class="reservation-title">{{ $reservation->equipment->name }}</h4>
                                            <div class="reservation-status">
                                                <span class="status-badge {{ $reservation->status }}">{{ ucfirst($reservation->status) }}</span>
                                            </div>
                                        </div>
                                        <div class="reservation-meta">
                                            <p class="reservation-period">
                                                <i class="fas fa-calendar"></i>
                                                {{ $reservation->reserved_from->format('M d, Y') }} - {{ $reservation->reserved_to->format('M d, Y') }}
                                            </p>
                                            <p class="reservation-quantity">
                                                <i class="fas fa-hashtag"></i>
                                                Quantity: {{ $reservation->quantity }}
                                            </p>
                                        </div>
                                        @if($reservation->status === 'reserved' || $reservation->status === 'borrowed')
                                            <div class="reservation-actions enhanced-actions">
                                                @if($reservation->status === 'reserved')
                                                    <a href="{{ route('equipment.reservations.edit', $reservation) }}" class="btn-modern outline edit-reservation">
                                                        <span>Edit</span>
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endif
                                                @if($reservation->status === 'reserved')
                                                    <form action="{{ route('equipment.reservations.destroy', $reservation) }}" method="POST" class="delete-reservation-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-modern danger" onclick="return confirm('Are you sure you want to delete this reservation?')">
                                                            <span>Delete</span>
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state enhanced-empty">
                            <div class="empty-animation">
                                <div class="empty-icon animated">
                                    <i class="fas fa-dumbbell"></i>
                                </div>
                                <div class="empty-particles">
                                    <div class="particle"></div>
                                    <div class="particle"></div>
                                    <div class="particle"></div>
                                </div>
                            </div>
                            <h4>No Equipment Reserved</h4>
                            <p>Reserve sports equipment for your activities!</p>
                            <a href="{{ route('equipment.index') }}" class="btn-modern primary glow">
                                <span>Browse Equipment</span>
                                <i class="fas fa-search"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Weather Widget -->
            <div class="content-card weather-card glass-effect">
                <div class="card-header enhanced-header">
                    <div class="header-content">
                        <h3 class="card-title">
                            <i class="fas fa-cloud-sun title-icon"></i>
                            Weather Today
                        </h3>
                    </div>
                </div>
                <div class="card-content">
                    <div class="weather-info">
                        <div class="weather-main">
                            <div class="weather-temp">
                                <span class="temp-value">22</span>
                                <span class="temp-unit">°C</span>
                            </div>
                            <div class="weather-condition">
                                <i class="fas fa-sun weather-icon"></i>
                                <span>Sunny</span>
                            </div>
                        </div>
                        <div class="weather-details">
                            <div class="weather-detail">
                                <i class="fas fa-eye"></i>
                                <span>Visibility: 10km</span>
                            </div>
                            <div class="weather-detail">
                                <i class="fas fa-wind"></i>
                                <span>Wind: 5 km/h</span>
                            </div>
                        </div>
                        <div class="weather-suggestion">
                            <i class="fas fa-info-circle"></i>
                            <span>Perfect weather for outdoor activities!</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced CSS with Advanced Features */
:root {
    --primary-color: #667eea;
    --primary-dark: #764ba2;
    --success-color: #48bb78;
    --warning-color: #ed8936;
    --info-color: #4299e1;
    --danger-color: #f56565;
    --dark-color: #1a202c;
    --light-color: #f7fafc;
    --text-primary: #1a202c;

    --text-secondary: #718096;
    --border-color: #e2e8f0;
    --shadow-color: rgba(0, 0, 0, 0.1);
    --backdrop-blur: blur(10px);
}

[data-theme="dark"] {
    --primary-color: #667eea;
    --text-primary: #f7fafc;
    --text-secondary: #cbd5e0;
    --border-color: #2d3748;
    --light-color: #1a202c;
    --shadow-color: rgba(0, 0, 0, 0.3);
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background: var(--light-color);
    color: var(--text-primary);
    line-height: 1.6;
    overflow-x: hidden;
    transition: all 0.3s ease;
}

/* Loading Overlay */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    transition: opacity 0.5s ease;
}

.loading-spinner {
    text-align: center;
    color: white;
}

.spinner-ring {
    width: 60px;
    height: 60px;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-top: 3px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 1rem;
}

.loading-text {
    font-size: 1.1rem;
    font-weight: 500;
    opacity: 0.9;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Performance Bar */
.performance-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: var(--backdrop-blur);
    border-radius: 12px;
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    border: 1px solid var(--border-color);
    box-shadow: 0 2px 10px var(--shadow-color);
}

.performance-metrics {
    display: flex;
    gap: 2rem;
}

.metric {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.metric-label {
    font-size: 0.75rem;
    color: var(--text-secondary);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.metric-value {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-primary);
}

.status-indicator.online {
    color: var(--success-color);
    position: relative;
}

.status-indicator.online::before {
    content: '';
    position: absolute;
    left: -12px;
    top: 50%;
    transform: translateY(-50%);
    width: 8px;
    height: 8px;
    background: var(--success-color);
    border-radius: 50%;
    animation: pulse 2s infinite;
}

.refresh-controls {
    display: flex;
    gap: 0.5rem;
}

.refresh-btn, .theme-toggle {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 8px;
    background: var(--primary-color);
    color: white;
    font-size: 0.875rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
}

.refresh-btn:hover, .theme-toggle:hover {
    background: var(--primary-dark);
    transform: translateY(-1px);
}

/* Dashboard Container */
.dashboard-container {
    padding: 2rem;
    max-width: 1400px;
    margin: 0 auto;
    min-height: 100vh;
    opacity: 0;
    transform: translateY(20px);
}

.dashboard-container.loaded {
    opacity: 1;
    transform: translateY(0);
    transition: all 0.8s ease;
}

/* Welcome Section with Enhanced Animation */
.welcome-section {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    border-radius: 24px;
    padding: 3rem;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
}

.welcome-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0.1;
}

.animated-shapes {
    position: absolute;
    width: 100%;
    height: 100%;
}

.shape {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    animation: float 6s ease-in-out infinite;
}

.shape-1 {
    width: 100px;
    height: 100px;
    top: 20%;
    left: 10%;
    animation-delay: 0s;
}

.shape-2 {
    width: 60px;
    height: 60px;
    top: 60%;
    right: 20%;
    animation-delay: 2s;
}

.shape-3 {
    width: 80px;
    height: 80px;
    bottom: 20%;
    left: 70%;
    animation-delay: 4s;
}

@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

.welcome-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    z-index: 2;
}

.welcome-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: white;
    margin: 0 0 1rem 0;
    line-height: 1.2;
}

.name-highlight {
    background: linear-gradient(45deg, #fff, #f0f8ff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    position: relative;
}

.typing-text {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.9);
    margin: 0 0 1.5rem 0;
    font-weight: 400;
    min-height: 1.5rem;
}

.welcome-stats {
    display: flex;
    gap: 1.5rem;
    margin-top: 1rem;
}

.quick-stat {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.875rem;
    font-weight: 500;
}

.floating-icon-container {
    position: relative;
    width: 100px;
    height: 100px;
}

.floating-icon {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    backdrop-filter: var(--backdrop-blur);
    border: 1px solid rgba(255, 255, 255, 0.3);
    position: relative;
    z-index: 2;
}

.icon-glow {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 120px;
    height: 120px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.3), transparent);
    border-radius: 50%;
    animation: glow 3s ease-in-out infinite;
}

@keyframes glow {
    0%, 100% { opacity: 0.5; transform: translate(-50%, -50%) scale(1); }
    50% { opacity: 1; transform: translate(-50%, -50%) scale(1.1); }
}

/* Enhanced Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card.modern-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: var(--backdrop-blur);
    border-radius: 20px;
    padding: 0;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.stat-card.modern-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
}

.card-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--accent-color), transparent);
    opacity: 0.05;
    transition: opacity 0.3s ease;
}

.stat-card:hover .card-background {
    opacity: 0.1;
}

.stat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 1.5rem 0;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--accent-color), var(--accent-color));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: #1a202c;
    position: relative;
}

.notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: var(--danger-color);
    color: white;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.625rem;
    font-weight: 600;
}

.stat-menu {
    color: var(--text-secondary);
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.stat-menu:hover {
    background: var(--border-color);
    color: var(--text-primary);
}

.stat-content {
    padding: 1rem 1.5rem;
}

.stat-label {
    font-size: 0.875rem;
    color: var(--text-secondary);
    font-weight: 500;
    display: block;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-value {
    font-size: 2.25rem;
    font-weight: 800;
    color: var(--text-primary);
    display: block;
    line-height: 1;
}

.stat-value.member-since {
    font-size: 1.5rem;
    font-weight: 700;
}

.stat-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0 1.5rem 1.5rem;
}

.trend-indicator {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.trend-indicator.positive {
    color: var(--success-color);
    background: rgba(72, 187, 120, 0.1);
}

.trend-indicator.negative {
    color: var(--danger-color);
    background: rgba(245, 101, 101, 0.1);
}

.trend-indicator.neutral {
    color: var(--text-secondary);
    background: rgba(113, 128, 150, 0.1);
}

.trend-text {
    font-size: 0.75rem;
    color: var(--text-secondary);
    margin-left: 0.5rem;
}

.mini-chart {
    width: 60px;
    height: 30px;
}

.member-progress {
    margin-top: 1rem;
}

.progress-bar {
    width: 100%;
    height: 4px;
    background: var(--border-color);
    border-radius: 2px;
    overflow: hidden;
}

.progress-bar.small {
    height: 3px;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
    border-radius: 2px;
    width: 0;
    transition: width 1.5s ease;
}

/* Glass Effect */
.glass-effect {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: var(--backdrop-blur);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

/* Enhanced Content Cards */
.main-content {
    display: flex;
    gap: 2.5rem;
    margin-top: 2rem;
}

.main-content-left {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.main-content-right {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.content-card {
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid var(--border-color);
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: var(--backdrop-blur);
}

.content-card:hover {
    box-shadow: 0 15px 45px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.priority-section {
    border: 2px solid var(--primary-color);
    box-shadow: 0 12px 35px rgba(102, 126, 234, 0.15);
}

/* Enhanced Content Cards */
/* Enhanced Sidebar with better spacing */
.sidebar-content {
    display: flex;
    flex-direction: column;
    gap: 2rem; /* Gentle spacing between cards */
}

/* Enhanced Content Cards */
.main-content {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 2.5rem;
}

.content-card {
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid var(--border-color);
}

.content-card:hover {
    box-shadow: 0 15px 45px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.priority-section {
    border: 2px solid var(--primary-color);
    box-shadow: 0 12px 35px rgba(102, 126, 234, 0.15);
}

.enhanced-header {
    padding: 2rem 2rem 1rem;
    border-bottom: 1px solid var(--border-color);
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.5));
}

.header-content {
    flex: 1;
}

.card-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 0.5rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.title-icon {
    font-size: 1.25rem;
    color: var(--primary-color);
}

.card-subtitle {
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin: 0;
    font-weight: 400;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.filter-btn {
    width: 40px;
    height: 40px;
    border: none;
    background: var(--border-color);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.2s ease;
}

.filter-btn:hover {
    background: var(--primary-color);
    color: white;
}

/* Enhanced Buttons */
.btn-modern {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.875rem;
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.btn-modern.primary {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
}

.btn-modern.outline {
    background: transparent;
    color: var(--primary-color);
    border: 1.5px solid var(--primary-color);
}

.btn-modern.small {
    padding: 0.5rem 1rem;
    font-size: 0.75rem;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
}

.btn-modern.pulse {
    animation: pulse-btn 2s infinite;
}

.btn-modern.glow {
    box-shadow: 0 0 20px rgba(102, 126, 234, 0.5);
}

@keyframes pulse-btn {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

/* Enhanced Events List */
.events-list.enhanced-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.event-item.advanced-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1.5rem;
    border: 1px solid var(--border-color);
    border-radius: 16px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.event-item.advanced-item:hover {
    border-color: var(--primary-color);
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.03), transparent);
    transform: translateX(8px);
}

.event-indicator {
    position: absolute;
    left: 0;
    top: 0;
    width: 4px;
    height: 100%;
    background: var(--primary-color);
    border-radius: 0 4px 4px 0;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.event-item.advanced-item:hover .event-indicator {
    opacity: 1;
}

.event-date.modern-date {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 70px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    border-radius: 16px;
    color: white;
    flex-shrink: 0;
    position: relative;
}

.date-day {
    font-size: 1.5rem;
    font-weight: 800;
    line-height: 1;
}

.date-month {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    opacity: 0.9;
    margin-top: 0.25rem;
}

.date-year {
    font-size: 0.625rem;
    opacity: 0.7;
}

.event-details.enhanced-details {
    flex: 1;
}

.event-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.75rem;
}

.event-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}

.priority-badge {
    font-size: 0.625rem;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.priority-badge.high {
    background: rgba(245, 101, 101, 0.1);
    color: var(--danger-color);
}

.event-meta {
    display: flex;
    gap: 1rem;
    margin-bottom: 0.75rem;
}

.event-time, .event-location {
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.event-description {
    font-size: 0.875rem;
    color: var(--text-secondary);
    margin: 0.75rem 0;
    line-height: 1.5;
}

.event-participants {
    margin-top: 1rem;
}

.participants-avatars {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.avatar {
    width: 30px;
    height: 30px;
    background: var(--primary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.75rem;
}

.participants-count {
    font-size: 0.75rem;
    color: var(--text-secondary);
    margin-left: 0.5rem;
}

.event-actions.enhanced-actions {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    align-items: flex-end;
}

.action-btn.quick-action {
    width: 40px;
    height: 40px;
    border: none;
    background: var(--border-color);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.2s ease;
}

.action-btn.quick-action:hover {
    background: var(--primary-color);
    color: white;
    transform: scale(1.1);
}

/* Enhanced Quick Actions */
.actions-grid.enhanced-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}

.action-btn.advanced-action {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    border: 1.5px solid var(--border-color);
    border-radius: 16px;
    text-decoration: none;
    color: var(--text-primary);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    background: white;
}

.action-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--accent-color), transparent);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.action-btn.advanced-action:hover {
    border-color: var(--accent-color);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(var(--accent-rgb), 0.2);
}

.action-btn.advanced-action:hover .action-background {
    opacity: 0.05;
}

.action-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--accent-color), var(--accent-color));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: white;
    position: relative;
    z-index: 2;
    transition: all 0.3s ease;
}

.notification-dot {
    position: absolute;
    top: -3px;
    right: -3px;
    width: 10px;
    height: 10px;
    background: var(--danger-color);
    border-radius: 50%;
    animation: pulse 2s infinite;
}

.action-content {
    flex: 1;
    text-align: left;
}

.action-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
    display: block;
    margin-bottom: 0.25rem;
}

.action-desc {
    font-size: 0.75rem;
    color: var(--text-secondary);
    display: block;
}

.action-arrow {
    color: var(--text-secondary);
    transition: all 0.3s ease;
    position: relative;
    z-index: 2;
}

.action-btn.advanced-action:hover .action-arrow {
    color: var(--accent-color);
    transform: translateX(4px);
}

/* Enhanced Timeline */
.reservations-timeline {
    position: relative;
}

.timeline-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1.5rem;
    position: relative;
}

.timeline-connector {
    position: absolute;
    left: 20px;
    top: 40px;
    bottom: -20px;
    width: 2px;
    background: linear-gradient(to bottom, var(--primary-color), transparent);
}

.timeline-item:last-child .timeline-connector {
    display: none;
}

.timeline-marker {
    width: 40px;
    height: 40px;
    background: var(--success-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.875rem;
    position: relative;
    z-index: 2;
    border: 3px solid white;
    box-shadow: 0 4px 12px rgba(72, 187, 120, 0.3);
}

.timeline-content {
    flex: 1;
    background: rgba(var(--accent-rgb), 0.05);
    padding: 1rem;
    border-radius: 12px;
    border: 1px solid var(--border-color);
}

/* Equipment Reservations Styles */
.reservation-item {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    transition: all 0.3s ease;
    position: relative;
}

.reservation-item:last-child {
    border-bottom: none;
}

.reservation-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-right: 1.5rem;
    flex-shrink: 0;
}

.reservation-indicator.reserved {
    background: var(--warning-color);
    box-shadow: 0 0 10px var(--warning-color);
}

.reservation-indicator.borrowed {
    background: var(--info-color);
    box-shadow: 0 0 10px var(--info-color);
}

.reservation-indicator.returned {
    background: var(--success-color);
    box-shadow: 0 0 10px var(--success-color);
}

.reservation-indicator.cancelled {
    background: var(--danger-color);
    box-shadow: 0 0 10px var(--danger-color);
}

.reservation-details {
    flex: 1;
}

.reservation-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.reservation-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.reservation-status .status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    text-transform: uppercase;
}

.reservation-status .status-badge.reserved {
    background: rgba(237, 137, 54, 0.15);
    color: var(--warning-color);
}

.reservation-status .status-badge.borrowed {
    background: rgba(66, 153, 225, 0.15);
    color: var(--info-color);
}

.reservation-status .status-badge.returned {
    background: rgba(72, 187, 120, 0.15);
    color: var(--success-color);
}

.reservation-status .status-badge.cancelled {
    background: rgba(245, 101, 101, 0.15);
    color: var(--danger-color);
}

.reservation-meta {
    display: flex;
    gap: 1.5rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.reservation-period,
.reservation-quantity {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.reservation-period i,
.reservation-quantity i {
    font-size: 0.8rem;
}

.reservation-actions {
    display: flex;
    gap: 0.75rem;
}

/* Button styles for reservation actions */
.reservation-actions .btn-modern {
    padding: 0.5rem 1rem;
    font-size: 0.85rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.reservation-actions .btn-modern.outline {
    background: transparent;
    border: 1px solid var(--border-color);
    color: var(--text-primary);
}

.reservation-actions .btn-modern.danger {
    background: rgba(245, 101, 101, 0.1);
    color: var(--danger-color);
    border: 1px solid rgba(245, 101, 101, 0.3);
}

.reservation-actions .btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.reservation-actions form {
    display: inline-block;
    margin: 0;
}

.reservation-item:hover {
    background: rgba(102, 126, 234, 0.03);
    transform: translateX(5px);
}

/* Responsive adjustments for reservations */
@media (max-width: 768px) {
    .reservation-item {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .reservation-indicator {
        margin-right: 0;
    }
    
    .reservation-header {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .reservation-meta {
        justify-content: center;
    }
    
    .reservation-actions {
        justify-content: center;
    }
}

/* Dark mode adjustments */
[data-theme="dark"] .reservation-item:hover {
    background: rgba(102, 126, 234, 0.1);
}

[data-theme="dark"] .reservation-actions .btn-modern.outline {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

[data-theme="dark"] .reservation-actions .btn-modern.danger {
    background: rgba(245, 101, 101, 0.2);
}

/* Weather Widget */
.weather-info {
    text-align: center;
}

.weather-main {
    margin-bottom: 1rem;
}

.weather-temp {
    display: flex;
    align-items: baseline;
    justify-content: center;
    gap: 0.25rem;
    margin-bottom: 0.5rem;
}

.temp-value {
    font-size: 3rem;
    font-weight: 800;
    color: var(--primary-color);
}

.temp-unit {
    font-size: 1.5rem;
    color: var(--text-secondary);
}

.weather-condition {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 1rem;
    color: var(--text-primary);
    font-weight: 500;
}

.weather-icon {
    font-size: 1.5rem;
    color: #fbbf24;
}

.weather-details {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
    margin: 1rem 0;
}

.weather-detail {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 0.75rem;
    color: var(--text-secondary);
}

.weather-suggestion {
    background: rgba(72, 187, 120, 0.1);
    color: var(--success-color);
    padding: 0.75rem;
    border-radius: 10px;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 1rem;
}

/* Enhanced Empty States */
.empty-state.enhanced-empty {
    text-align: center;
    padding: 3rem 2rem;
    position: relative;
}

.empty-animation {
    position: relative;
    margin-bottom: 2rem;
}

.empty-icon.animated {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: white;
    margin: 0 auto;
    animation: bounce 2s infinite;
}

.empty-particles {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.particle {
    position: absolute;
    width: 4px;
    height: 4px;
    background: var(--primary-color);
    border-radius: 50%;
    opacity: 0.7;
    animation: particle-float 3s infinite;
}

.particle:nth-child(1) {
    top: -40px;
    left: -20px;
    animation-delay: 0s;
}

.particle:nth-child(2) {
    top: -30px;
    right: -25px;
    animation-delay: 1s;
}

.particle:nth-child(3) {
    bottom: -35px;
    left: -15px;
    animation-delay: 2s;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-20px); }
    60% { transform: translateY(-10px); }
}

@keyframes particle-float {
    0%, 100% { transform: translateY(0); opacity: 0.7; }
    50% { transform: translateY(-20px); opacity: 1; }
}

@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.7; transform: scale(1.2); }
}

/* Responsive Design */
@media (max-width: 1200px) {
    .main-content {
        grid-template-columns: 1fr;
    }
    
    .sidebar-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    
    .performance-bar {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .performance-metrics {
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .dashboard-container {
        padding: 1rem;
    }
    
    .welcome-section {
        padding: 2rem 1.5rem;
    }
    
    .welcome-content {
        flex-direction: column;
        text-align: center;
        gap: 1.5rem;
    }
    
    .welcome-title {
        font-size: 2rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .enhanced-header {
        padding: 1.5rem;
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }
    
    .header-actions {
        justify-content: center;
    }
    
    .event-item.advanced-item {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .event-meta {
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .performance-metrics {
        flex-direction: column;
        gap: 1rem;
    }
    
    .actions-grid.enhanced-grid {
        gap: 0.75rem;
    }
}

@media (max-width: 480px) {
    .welcome-title {
        font-size: 1.75rem;
    }
    
    .stat-value {
        font-size: 2rem;
    }
    
    .card-title {
        font-size: 1.25rem;
    }
    
    .enhanced-header {
        padding: 1rem;
    }
    
    .card-content {
        padding: 1rem;
    }
}

/* Dark mode styles */
[data-theme="dark"] .welcome-section {
    background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
}

[data-theme="dark"] .stat-card.modern-card,
[data-theme="dark"] .content-card {
    background: rgba(45, 55, 72, 0.95);
    border-color: rgba(255, 255, 255, 0.1);
}

[data-theme="dark"] .performance-bar {
    background: rgba(45, 55, 72, 0.9);
}

[data-theme="dark"] .action-btn.advanced-action {
    background: rgba(45, 55, 72, 0.9);
}

[data-theme="dark"] .timeline-content {
    background: rgba(45, 55, 72, 0.5);
}

[data-theme="dark"] .stat-icon {
    color: var(--icon-light-bg);
}

[data-theme="dark"] .enhanced-header {
    background: linear-gradient(135deg, rgba(1, 21, 28, 0.8), rgba(255, 255, 255, 0.5));
}
/* Accessibility */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* Print styles */
@media print {
    .performance-bar,
    .floating-icon-container,
    .header-actions,
    .action-btn,
    .btn-modern {
        display: none !important;
    }
    
    .dashboard-container {
        padding: 1rem;
    }
    
    .welcome-section {
        background: #f7fafc !important;
        color: #1a202c !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get elements first
    const loadingOverlay = document.getElementById('loadingOverlay');
    const dashboardContainer = document.getElementById('dashboardContainer');
    
    // Fallback function to hide loading
    function hideLoading() {
        if (loadingOverlay) {
            loadingOverlay.style.opacity = '0';
            loadingOverlay.style.visibility = 'hidden';
            loadingOverlay.style.pointerEvents = 'none';
            setTimeout(() => {
                loadingOverlay.style.display = 'none';
            }, 500);
        }
        if (dashboardContainer) {
            dashboardContainer.classList.add('loaded');
        }
    }
    
    // Immediate fallback - hide loading after 1 second regardless
    setTimeout(hideLoading, 1000);
    
    // Check if GSAP is loaded
    if (typeof gsap !== 'undefined') {
        try {
            // Register GSAP plugins
            gsap.registerPlugin(ScrollTrigger, TextPlugin);
            
            // Hide loading with GSAP animation after 1.5 seconds
            setTimeout(() => {
                if (loadingOverlay && dashboardContainer) {
                    gsap.to(loadingOverlay, {
                        duration: 0.5,
                        opacity: 0,
                        onComplete: () => {
                            loadingOverlay.style.display = 'none';
                            dashboardContainer.classList.add('loaded');
                            initDashboardAnimations();
                        }
                    });
                }
            }, 1500);
        } catch (error) {
            console.warn('GSAP animation error, using fallback:', error);
            hideLoading();
        }
    } else {
        console.warn('GSAP not loaded, using fallback');
        hideLoading();
    }
    
    function initDashboardAnimations() {
        try {
            // Main timeline
            const tl = gsap.timeline();
            
            // Performance bar animation
            tl.from('.performance-bar', {
                duration: 0.8,
                y: -50,
                opacity: 0,
                ease: 'power3.out'
            })
            
            // Welcome section animation
            .from('.welcome-section', {
                duration: 1,
                y: 30,
                opacity: 0,
                ease: 'power3.out'
            }, '-=0.3')
            
            // Typing effect for welcome text
            .to('.typing-text', {
                duration: 2,
                text: "Here's what's happening with your sports club activities today.",
                ease: 'none'
            }, '-=0.5')
            
            // Stats cards animation
            .from('.stat-card', {
                duration: 0.8,
                y: 50,
                opacity: 0,
                stagger: 0.1,
                ease: 'power3.out'
            }, '-=0.5')
            
            // Content cards animation
            .from('.content-card', {
                duration: 0.8,
                y: 40,
                opacity: 0,
                stagger: 0.15,
                ease: 'power3.out'
            }, '-=0.3');
            
            // Floating icon animation
            gsap.to('.floating-icon', {
                duration: 4,
                y: -15,
                rotation: 10,
                repeat: -1,
                yoyo: true,
                ease: 'power2.inOut'
            });
            
            // Animated shapes
            gsap.to('.shape', {
                duration: 6,
                rotation: 360,
                repeat: -1,
                ease: 'none',
                stagger: 1
            });
            
            // Counter animations
            function animateCounters() {
                document.querySelectorAll('.counter[data-target]').forEach(counter => {
                    const target = parseInt(counter.dataset.target) || 0;
                    // Set initial text content to 0 for animation
                    counter.textContent = '0';
                    gsap.to({value: 0}, {
                        duration: 2,
                        value: target,
                        ease: 'power2.out',
                        onUpdate: function() {
                            counter.textContent = Math.round(this.targets()[0].value);
                        },
                        delay: 1
                    });
                });
            }
            animateCounters();
            
            // Progress bars animation
            function animateProgressBars() {
                document.querySelectorAll('.progress-fill[data-progress]').forEach(bar => {
                    const progress = parseInt(bar.dataset.progress);
                    gsap.to(bar, {
                        duration: 1.5,
                        width: `${progress}%`,
                        ease: 'power2.out',
                        delay: 1.5
                    });
                });
            }
            animateProgressBars();
            
            // ScrollTrigger animations
            gsap.fromTo('.event-item', 
                { x: -50, opacity: 0 },
                {
                    x: 0,
                    opacity: 1,
                    duration: 0.6,
                    stagger: 0.1,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: '.events-list',
                        start: 'top 80%',
                        end: 'bottom 20%',
                        toggleActions: 'play none none none'
                    }
                }
            );
            
            gsap.fromTo('.timeline-item', 
                { x: 50, opacity: 0 },
                {
                    x: 0,
                    opacity: 1,
                    duration: 0.6,
                    stagger: 0.15,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: '.reservations-timeline',
                        start: 'top 80%',
                        end: 'bottom 20%',
                        toggleActions: 'play none none none'
                    }
                }
            );
            
            gsap.fromTo('.action-btn.advanced-action', 
                { scale: 0.8, opacity: 0 },
                {
                    scale: 1,
                    opacity: 1,
                    duration: 0.6,
                    stagger: 0.1,
                    ease: 'back.out(1.7)',
                    scrollTrigger: {
                        trigger: '.actions-grid',
                        start: 'top 80%',
                        end: 'bottom 20%',
                        toggleActions: 'play none none none'
                    }
                }
            );
        } catch (error) {
            console.warn('Animation initialization error:', error);
        }
    }
    
    // Hover animations
    document.querySelectorAll('.stat-card').forEach(card => {
        const icon = card.querySelector('.stat-icon');
        const background = card.querySelector('.card-background');
        
        card.addEventListener('mouseenter', () => {
            if (typeof gsap !== 'undefined') {
                gsap.to(card, {
                    duration: 0.3,
                    scale: 1.02,
                    ease: 'power2.out'
                });
                
                if (icon) {
                    gsap.to(icon, {
                        duration: 0.3,
                        scale: 1.1,
                        rotation: 5,
                        ease: 'power2.out'
                    });
                }
                
                if (background) {
                    gsap.to(background, {
                        duration: 0.3,
                        opacity: 0.1,
                        ease: 'power2.out'
                    });
                }
            }
        });
        
        card.addEventListener('mouseleave', () => {
            if (typeof gsap !== 'undefined') {
                gsap.to(card, {
                    duration: 0.3,
                    scale: 1,
                    ease: 'power2.out'
                });
                
                if (icon) {
                    gsap.to(icon, {
                        duration: 0.3,
                        scale: 1,
                        rotation: 0,
                        ease: 'power2.out'
                    });
                }
                
                if (background) {
                    gsap.to(background, {
                        duration: 0.3,
                        opacity: 0.05,
                        ease: 'power2.out'
                    });
                }
            }
        });
    });
    
    // Button hover animations
    document.querySelectorAll('.btn-modern').forEach(btn => {
        btn.addEventListener('mouseenter', () => {
            if (typeof gsap !== 'undefined') {
                gsap.to(btn, {
                    duration: 0.2,
                    scale: 1.05,
                    ease: 'power2.out'
                });
            }
        });
        
        btn.addEventListener('mouseleave', () => {
            if (typeof gsap !== 'undefined') {
                gsap.to(btn, {
                    duration: 0.2,
                    scale: 1,
                    ease: 'power2.out'
                });
            }
        });
    });
    
    // Theme toggle functionality
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        const currentTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', currentTheme);
        
        themeToggle.addEventListener('click', () => {
            const theme = document.documentElement.getAttribute('data-theme');
            const newTheme = theme === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            const icon = themeToggle.querySelector('i');
            if (icon) {
                icon.className = newTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            }
            
            // Theme transition animation
            if (typeof gsap !== 'undefined') {
                gsap.to('body', {
                    duration: 0.3,
                    ease: 'power2.inOut'
                });
            }
        });
    }
    
    // Refresh functionality
    const refreshBtn = document.getElementById('refreshData');
    const lastUpdatedElement = document.getElementById('lastUpdated');
    
    if (refreshBtn && lastUpdatedElement) {
        refreshBtn.addEventListener('click', () => {
            // Animate refresh button
            if (typeof gsap !== 'undefined') {
                gsap.to(refreshBtn.querySelector('i'), {
                    duration: 1,
                    rotation: 360,
                    ease: 'power2.inOut'
                });
            }
            
            // Update last updated time
            lastUpdatedElement.textContent = 'Just now';
            
            // Re-animate counters
            animateCounters();
            
            // Simulate data refresh animation
            if (typeof gsap !== 'undefined') {
                document.querySelectorAll('.stat-value').forEach(value => {
                    gsap.to(value, {
                        duration: 0.3,
                        scale: 1.1,
                        yoyo: true,
                        repeat: 1,
                        ease: 'power2.inOut'
                    });
                });
            }
        });
    }
    
    // Update time every minute
    if (lastUpdatedElement) {
        setInterval(() => {
            const now = new Date();
            const lastUpdate = new Date(lastUpdatedElement.getAttribute('data-time') || now);
            const diff = Math.floor((now - lastUpdate) / 60000);
            
            if (diff > 0) {
                lastUpdatedElement.textContent = `${diff} min ago`;
            }
        }, 60000);
    }
    
    // Pulse animations for status indicators
    if (typeof gsap !== 'undefined') {
        gsap.to('.notification-badge, .notification-dot, .status-dot', {
            duration: 2,
            scale: 1.2,
            opacity: 0.8,
            repeat: -1,
            yoyo: true,
            ease: 'power2.inOut'
        });
    }
    
    // Particle system for empty states
    function createParticles() {
        if (typeof gsap !== 'undefined') {
            const emptyStates = document.querySelectorAll('.empty-particles');
            emptyStates.forEach(container => {
                for (let i = 0; i < 5; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'particle';
                    particle.style.left = `${Math.random() * 100 - 50}px`;
                    particle.style.top = `${Math.random() * 100 - 50}px`;
                    container.appendChild(particle);
                    
                    gsap.to(particle, {
                        duration: 2 + Math.random() * 2,
                        y: -30,
                        opacity: 0,
                        repeat: -1,
                        delay: Math.random() * 2,
                        ease: 'power2.out'
                    });
                }
            });
        }
    }
    createParticles();
    
    // Performance monitoring
    if (typeof PerformanceObserver !== 'undefined') {
        try {
            const observer = new PerformanceObserver((list) => {
                for (const entry of list.getEntries()) {
                    if (entry.entryType === 'navigation') {
                        const responseTime = Math.round(entry.responseStart - entry.requestStart);
                        const responseElement = document.querySelector('.metric-value:last-child');
                        if (responseElement) {
                            responseElement.textContent = `< ${responseTime}ms`;
                        }
                    }
                }
            });
            observer.observe({ entryTypes: ['navigation'] });
        } catch (error) {
            console.warn('Performance observer error:', error);
        }
    }
});
</script>
@endsection