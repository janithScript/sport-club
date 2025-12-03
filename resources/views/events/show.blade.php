@extends('layouts.app')

@section('title', $event->title . ' - Sports Club Management')

@section('head')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection

@section('content')
<div class="event-detail-container">
    <!-- Theme Toggle Button -->
    <div class="theme-toggle-wrapper">
        <button class="theme-toggle" id="themeToggle">
            <i class="fas fa-moon"></i>
        </button>
    </div>

    <!-- Header Section -->
    <div class="event-header-section">
        <div class="header-content">
            <nav class="breadcrumb-nav">
                <a href="{{ route('events.index') }}" class="breadcrumb-link">
                    <i class="fas fa-arrow-left"></i> Back to Events
                </a>
            </nav>
            <h1 class="event-title">{{ $event->title }}</h1>
            <div class="event-status-badge">
                @if($event->start_at->isPast())
                    <span class="status-badge past">
                        <i class="fas fa-history"></i> Event Completed
                    </span>
                @elseif($event->start_at->isToday())
                    <span class="status-badge today">
                        <i class="fas fa-clock"></i> Happening Today
                    </span>
                @else
                    <span class="status-badge upcoming">
                        <i class="fas fa-calendar-check"></i> Upcoming Event
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="event-content">
        <!-- Main Event Details -->
        <div class="event-main">
            <div class="event-details-card modern-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle title-icon"></i>
                        Event Details
                    </h3>
                </div>
                <div class="card-content">
                    <div class="details-grid">
                        <div class="detail-group">
                            <div class="detail-item">
                                <span class="detail-icon">
                                    <i class="fas fa-calendar-day"></i>
                                </span>
                                <div class="detail-content">
                                    <span class="detail-label">Start Date</span>
                                    <span class="detail-value">{{ $event->start_at->format('M d, Y g:i A') }}</span>
                                </div>
                            </div>
                            <div class="detail-item">
                                <span class="detail-icon">
                                    <i class="fas fa-calendar-check"></i>
                                </span>
                                <div class="detail-content">
                                    <span class="detail-label">End Date</span>
                                    <span class="detail-value">{{ $event->end_at->format('M d, Y g:i A') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="detail-group">
                            @if($event->location)
                                <div class="detail-item">
                                    <span class="detail-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </span>
                                    <div class="detail-content">
                                        <span class="detail-label">Location</span>
                                        <span class="detail-value">{{ $event->location }}</span>
                                    </div>
                                </div>
                            @endif
                            @if($event->capacity > 0)
                                <div class="detail-item">
                                    <span class="detail-icon">
                                        <i class="fas fa-users"></i>
                                    </span>
                                    <div class="detail-content">
                                        <span class="detail-label">Capacity</span>
                                        <span class="detail-value">{{ $event->capacity }} people</span>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-icon availability @if($event->available_spots > 0) available @else full @endif">
                                        <i class="fas fa-ticket-alt"></i>
                                    </span>
                                    <div class="detail-content">
                                        <span class="detail-label">Available Spots</span>
                                        <span class="detail-value @if($event->available_spots > 0) available @else full @endif">
                                            {{ $event->available_spots ?? 0 }}
                                            @if($event->available_spots <= 0)
                                                (Full)
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($event->description)
                        <div class="description-section">
                            <h5 class="section-title">Description</h5>
                            <div class="description-content">
                                <p>{{ $event->description }}</p>
                            </div>
                        </div>
                    @endif

                    @auth
                        @if(!$event->start_at->isPast())
                            <div class="registration-section">
                                @if(!$isRegistered)
                                    @if(!$event->isFull())
                                        <form action="{{ route('events.register', $event) }}" method="POST" 
                                              x-data="{ loading: false }" 
                                              @submit="loading = true">
                                            @csrf
                                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                                            <button type="submit" class="btn-modern success large" 
                                                    :disabled="loading"
                                                    :class="{ 'loading': loading }">
                                                <span x-show="!loading" class="btn-content">
                                                    <i class="fas fa-user-plus"></i>
                                                    Register for Event
                                                </span>
                                                <span x-show="loading" class="btn-spinner">
                                                    <i class="fas fa-spinner fa-spin"></i>
                                                    Registering...
                                                </span>
                                            </button>
                                        </form>
                                    @else
                                        <div class="alert-message danger">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <span>This event is full. No more spots available.</span>
                                        </div>
                                    @endif
                                @else
                                    <div class="alert-message success">
                                        <i class="fas fa-check-circle"></i>
                                        <span>You are registered for this event!</span>
                                    </div>
                                    <form action="{{ route('events.unregister', $event) }}" method="POST" 
                                          x-data="{ loading: false }" 
                                          @submit="loading = true"
                                          onsubmit="return confirm('Are you sure you want to cancel your registration?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-modern danger outline" 
                                                :disabled="loading"
                                                :class="{ 'loading': loading }">
                                            <span x-show="!loading" class="btn-content">
                                                <i class="fas fa-user-times"></i>
                                                Cancel Registration
                                            </span>
                                            <span x-show="loading" class="btn-spinner">
                                                <i class="fas fa-spinner fa-spin"></i>
                                                Canceling...
                                            </span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @else
                            <div class="alert-message info">
                                <i class="fas fa-info-circle"></i>
                                <span>This event has already ended.</span>
                            </div>
                        @endif
                    @else
                        <div class="alert-message info">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>
                                <a href="{{ route('login') }}" class="alert-link">Login</a> or 
                                <a href="{{ route('register') }}" class="alert-link">register</a> to join this event.
                            </span>
                        </div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="event-sidebar">
            <!-- Event Statistics -->
            <div class="stats-card modern-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar title-icon"></i>
                        Event Statistics
                    </h3>
                </div>
                <div class="card-content">
                    <div class="stat-item large">
                        <div class="stat-icon registered">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="stat-content">
                            <span class="stat-number">{{ $event->confirmedRegistrations->count() }}</span>
                            <span class="stat-label">Registered Members</span>
                        </div>
                    </div>
                    @if($event->capacity > 0)
                        <div class="stat-item large">
                            <div class="stat-icon availability @if($event->available_spots > 0) available @else full @endif">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                            <div class="stat-content">
                                <span class="stat-number @if($event->available_spots > 0) available @else full @endif">
                                    {{ $event->available_spots ?? 0 }}
                                </span>
                                <span class="stat-label">Available Spots</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Registered Members -->
            @if($event->confirmedRegistrations->count() > 0)
                <div class="members-card modern-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-users title-icon"></i>
                            Registered Members
                        </h3>
                    </div>
                    <div class="card-content">
                        <div class="members-list">
                            @foreach($event->confirmedRegistrations->take(10) as $registration)
                                <div class="member-item">
                                    <div class="member-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="member-info">
                                        <span class="member-name">{{ $registration->user->name }}</span>
                                        <span class="member-date">Registered {{ $registration->registered_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            @endforeach
                            
                            @if($event->confirmedRegistrations->count() > 10)
                                <div class="members-overflow">
                                    <i class="fas fa-plus-circle"></i>
                                    <span>{{ $event->confirmedRegistrations->count() - 10 }} more members...</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
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
    --card-bg: rgba(255, 255, 255, 0.95);
}

[data-theme="dark"] {
    --primary-color: #667eea;
    --text-primary: #f7fafc;
    --text-secondary: #cbd5e0;
    --border-color: #2d3748;
    --light-color: #1a202c;
    --shadow-color: rgba(0, 0, 0, 0.3);
    --card-bg: rgba(45, 55, 72, 0.95);
}

* {
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background: var(--light-color);
    color: var(--text-primary);
    line-height: 1.6;
    transition: all 0.3s ease;
}

.event-detail-container {
    padding: 2rem;
    max-width: 1400px;
    margin: 0 auto;
    min-height: 100vh;
    position: relative;
}

/* Theme Toggle */
.theme-toggle-wrapper {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
}

.theme-toggle {
    width: 50px;
    height: 50px;
    border: none;
    border-radius: 50%;
    background: var(--primary-color);
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.theme-toggle:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

/* Header Section */
.event-header-section {
    margin-bottom: 2rem;
}

.breadcrumb-nav {
    margin-bottom: 1rem;
}

.breadcrumb-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease;
}

.breadcrumb-link:hover {
    color: var(--primary-dark);
    transform: translateX(-2px);
    text-decoration: none;
}

.event-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0 0 1rem 0;
    line-height: 1.2;
}

.event-status-badge {
    margin-bottom: 1rem;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge.past {
    background: rgba(113, 128, 150, 0.1);
    color: var(--text-secondary);
}

.status-badge.today {
    background: rgba(237, 137, 54, 0.1);
    color: var(--warning-color);
}

.status-badge.upcoming {
    background: rgba(72, 187, 120, 0.1);
    color: var(--success-color);
}

/* Event Content */
.event-content {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 2rem;
}

/* Modern Cards */
.modern-card {
    background: var(--card-bg);
    backdrop-filter: var(--backdrop-blur);
    border-radius: 20px;
    box-shadow: 0 8px 30px var(--shadow-color);
    border: 1px solid var(--border-color);
    overflow: hidden;
    transition: all 0.3s ease;
}

.modern-card:hover {
    box-shadow: 0 15px 45px var(--shadow-color);
    transform: translateY(-2px);
}

.card-header {
    padding: 1.5rem 1.5rem 0;
    border-bottom: 1px solid var(--border-color);
    margin-bottom: 1.5rem;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 1rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.title-icon {
    font-size: 1rem;
    color: var(--primary-color);
    background: rgba(102, 126, 234, 0.1);
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card-content {
    padding: 0 1.5rem 1.5rem;
}

/* Details Grid */
.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.detail-group {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(102, 126, 234, 0.05);
    border-radius: 12px;
    border: 1px solid rgba(102, 126, 234, 0.1);
}

.detail-icon {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}

.detail-icon.availability.available {
    background: linear-gradient(135deg, var(--success-color), #38a169);
}

.detail-icon.availability.full {
    background: linear-gradient(135deg, var(--danger-color), #e53e3e);
}

.detail-content {
    display: flex;
    flex-direction: column;
}

.detail-label {
    font-size: 0.75rem;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.detail-value {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-primary);
}

.detail-value.available {
    color: var(--success-color);
}

.detail-value.full {
    color: var(--danger-color);
}

/* Description Section */
.description-section {
    margin-bottom: 2rem;
    padding-top: 2rem;
    border-top: 1px solid var(--border-color);
}

.section-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.description-content p {
    color: var(--text-secondary);
    line-height: 1.6;
    margin: 0;
}

/* Registration Section */
.registration-section {
    padding-top: 2rem;
    border-top: 1px solid var(--border-color);
}

/* Modern Buttons */
.btn-modern {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 2rem;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.875rem;
    text-decoration: none;
    transition: all 0.2s ease;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    margin-bottom: 1rem;
}

.btn-modern.success {
    background: linear-gradient(135deg, var(--success-color), #38a169);
    color: white;
}

.btn-modern.danger {
    background: linear-gradient(135deg, var(--danger-color), #e53e3e);
    color: white;
}

.btn-modern.outline {
    background: transparent;
    border: 2px solid var(--danger-color);
    color: var(--danger-color);
}

.btn-modern.large {
    padding: 1.25rem 2rem;
    font-size: 1rem;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.btn-modern:disabled {
    opacity: 0.7;
    transform: none;
    cursor: not-allowed;
}

.btn-spinner {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

/* Alert Messages */
.alert-message {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.25rem;
    border-radius: 12px;
    font-weight: 500;
    margin-bottom: 1rem;
}

.alert-message.success {
    background: rgba(72, 187, 120, 0.1);
    color: var(--success-color);
    border: 1px solid rgba(72, 187, 120, 0.2);
}

.alert-message.danger {
    background: rgba(245, 101, 101, 0.1);
    color: var(--danger-color);
    border: 1px solid rgba(245, 101, 101, 0.2);
}

.alert-message.info {
    background: rgba(66, 153, 225, 0.1);
    color: var(--info-color);
    border: 1px solid rgba(66, 153, 225, 0.2);
}

.alert-link {
    color: inherit;
    text-decoration: underline;
    font-weight: 600;
}

.alert-link:hover {
    text-decoration: none;
}

/* Stats Section */
.stat-item.large {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
    background: rgba(102, 126, 234, 0.05);
    border-radius: 12px;
    border: 1px solid rgba(102, 126, 234, 0.1);
    margin-bottom: 1rem;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.stat-icon.registered {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
}

.stat-icon.availability.available {
    background: linear-gradient(135deg, var(--success-color), #38a169);
    color: white;
}

.stat-icon.availability.full {
    background: linear-gradient(135deg, var(--danger-color), #e53e3e);
    color: white;
}

.stat-content {
    display: flex;
    flex-direction: column;
}

.stat-number {
    font-size: 1.75rem;
    font-weight: 800;
    color: var(--text-primary);
    line-height: 1;
}

.stat-number.available {
    color: var(--success-color);
}

.stat-number.full {
    color: var(--danger-color);
}

.stat-label {
    font-size: 0.875rem;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    margin-top: 0.25rem;
}

/* Members Section */
.members-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.member-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(102, 126, 234, 0.05);
    border-radius: 12px;
    border: 1px solid rgba(102, 126, 234, 0.1);
    transition: all 0.2s ease;
}

.member-item:hover {
    background: rgba(102, 126, 234, 0.1);
}

.member-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.member-info {
    display: flex;
    flex-direction: column;
}

.member-name {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.member-date {
    font-size: 0.75rem;
    color: var(--text-secondary);
}

.members-overflow {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    background: rgba(102, 126, 234, 0.05);
    border-radius: 12px;
    border: 1px solid rgba(102, 126, 234, 0.1);
    color: var(--text-secondary);
    font-size: 0.875rem;
    font-weight: 500;
    text-align: center;
    justify-content: center;
}

/* Dark Mode Specific Styles */
[data-theme="dark"] .detail-item,
[data-theme="dark"] .stat-item.large,
[data-theme="dark"] .member-item,
[data-theme="dark"] .members-overflow {
    background: rgba(45, 55, 72, 0.5);
    border-color: rgba(255, 255, 255, 0.1);
}

[data-theme="dark"] .title-icon {
    background: rgba(102, 126, 234, 0.2);
}

/* Responsive Design */
@media (max-width: 1024px) {
    .event-content {
        grid-template-columns: 1fr;
    }
    
    .details-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .event-detail-container {
        padding: 1rem;
    }
    
    .theme-toggle-wrapper {
        top: 10px;
        right: 10px;
    }
    
    .theme-toggle {
        width: 45px;
        height: 45px;
        font-size: 1.1rem;
    }
    
    .event-title {
        font-size: 2rem;
    }
    
    .details-grid {
        gap: 1rem;
    }
    
    .detail-item {
        flex-direction: column;
        text-align: center;
        gap: 0.75rem;
    }
    
    .btn-modern {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .event-title {
        font-size: 1.75rem;
    }
    
    .modern-card {
        margin: 0;
    }
    
    .card-content {
        padding: 0 1rem 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Theme toggle functionality
    const themeToggle = document.getElementById('themeToggle');
    const currentTheme = localStorage.getItem('theme') || 'light';
    
    // Set initial theme
    document.documentElement.setAttribute('data-theme', currentTheme);
    updateThemeIcon(currentTheme);
    
    themeToggle.addEventListener('click', () => {
        const theme = document.documentElement.getAttribute('data-theme');
        const newTheme = theme === 'dark' ? 'light' : 'dark';
        
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon(newTheme);
    });
    
    function updateThemeIcon(theme) {
        const icon = themeToggle.querySelector('i');
        icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    }
});
</script>
@endsection