@extends('layouts.app')

@section('title', 'Events - Sports Club Management')

@section('head')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
@endsection

@section('content')
<div class="events-container">
    <!-- Theme Toggle Button -->
    <div class="theme-toggle-wrapper">
        <button class="theme-toggle" id="themeToggle">
            <i class="fas fa-moon"></i>
        </button>
    </div>

    <!-- Header Section -->
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">
                <i class="fas fa-calendar-alt title-icon"></i>
                Sports Events
            </h1>
            <p class="page-subtitle">Discover and join exciting sports activities</p>
        </div>
        @auth
            @if(auth()->user()->is_admin)
                <a href="/admin/events/create" class="btn-modern primary">
                    <i class="fas fa-plus"></i>
                    <span>Create Event</span>
                </a>
            @endif
        @endauth
    </div>

    @if($events->count() > 0)
        <div class="events-grid">
            @foreach($events as $event)
                <div class="event-card modern-card">
                    <div class="event-status-indicator @if($event->start_at->isPast()) past @elseif($event->start_at->isToday()) today @else upcoming @endif"></div>
                    
                    <div class="event-header">
                        <h3 class="event-title">{{ $event->title }}</h3>
                        <div class="event-badge">
                            @if($event->start_at->isPast())
                                <span class="badge past">
                                    <i class="fas fa-history"></i> Past
                                </span>
                            @elseif($event->start_at->isToday())
                                <span class="badge today">
                                    <i class="fas fa-clock"></i> Today
                                </span>
                            @else
                                <span class="badge upcoming">
                                    <i class="fas fa-calendar-check"></i> Upcoming
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="event-meta">
                        <div class="meta-item">
                            <i class="fas fa-calendar-day"></i>
                            <span>{{ $event->start_at->format('M d, Y') }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <span>{{ $event->start_at->format('g:i A') }} - {{ $event->end_at->format('g:i A') }}</span>
                        </div>
                        @if($event->location)
                            <div class="meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $event->location }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="event-description">
                        <p>{{ Str::limit($event->description, 120) }}</p>
                    </div>
                    
                    <div class="event-stats">
                        <div class="stat-group">
                            @if($event->capacity > 0)
                                <div class="stat-item capacity">
                                    <span class="stat-icon @if($event->available_spots > 0) available @else full @endif">
                                        <i class="fas fa-users"></i>
                                    </span>
                                    <div class="stat-content">
                                        @if($event->available_spots > 0)
                                            <span class="stat-number">{{ $event->available_spots }}</span>
                                            <span class="stat-label">spots left</span>
                                        @else
                                            <span class="stat-number full">Full</span>
                                            <span class="stat-label">capacity reached</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            
                            <div class="stat-item registered">
                                <span class="stat-icon">
                                    <i class="fas fa-user-check"></i>
                                </span>
                                <div class="stat-content">
                                    <span class="stat-number">{{ $event->confirmedRegistrations->count() }}</span>
                                    <span class="stat-label">registered</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="event-actions">
                        <a href="{{ route('events.show', $event) }}" class="btn-modern primary full-width">
                            <span>View Details</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $events->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-calendar-times"></i>
            </div>
            <h3 class="empty-title">No events available</h3>
            <p class="empty-message">Check back later for upcoming events!</p>
        </div>
    @endif
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

.events-container {
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

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 3rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--border-color);
}

.header-content {
    flex: 1;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0 0 0.5rem 0;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.title-icon {
    font-size: 2rem;
    color: var(--primary-color);
    background: rgba(102, 126, 234, 0.1);
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.page-subtitle {
    font-size: 1.1rem;
    color: var(--text-secondary);
    margin: 0;
    font-weight: 400;
}

/* Modern Button */
.btn-modern {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.875rem 1.5rem;
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
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-modern.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    text-decoration: none;
    color: white;
}

.btn-modern.full-width {
    width: 100%;
    justify-content: center;
}

/* Events Grid */
.events-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

/* Event Cards */
.event-card.modern-card {
    background: var(--card-bg);
    backdrop-filter: var(--backdrop-blur);
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 8px 30px var(--shadow-color);
    border: 1px solid var(--border-color);
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.event-card.modern-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 50px var(--shadow-color);
}

.event-status-indicator {
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    border-radius: 0 4px 4px 0;
}

.event-status-indicator.past {
    background: var(--text-secondary);
}

.event-status-indicator.today {
    background: var(--warning-color);
}

.event-status-indicator.upcoming {
    background: var(--success-color);
}

.event-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.event-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
    flex: 1;
    line-height: 1.3;
}

.event-badge .badge {
    font-size: 0.75rem;
    padding: 0.5rem 0.75rem;
    border-radius: 20px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge.past {
    background: rgba(113, 128, 150, 0.1);
    color: var(--text-secondary);
}

.badge.today {
    background: rgba(237, 137, 54, 0.1);
    color: var(--warning-color);
}

.badge.upcoming {
    background: rgba(72, 187, 120, 0.1);
    color: var(--success-color);
}

.event-meta {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.meta-item i {
    width: 16px;
    color: var(--primary-color);
}

.event-description {
    margin-bottom: 1.5rem;
}

.event-description p {
    color: var(--text-secondary);
    margin: 0;
    line-height: 1.5;
}

.event-stats {
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: rgba(102, 126, 234, 0.05);
    border-radius: 12px;
    border: 1px solid rgba(102, 126, 234, 0.1);
}

.stat-group {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex: 1;
}

.stat-icon {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
}

.stat-icon.available {
    background: rgba(72, 187, 120, 0.1);
    color: var(--success-color);
}

.stat-icon.full {
    background: rgba(245, 101, 101, 0.1);
    color: var(--danger-color);
}

.stat-item.registered .stat-icon {
    background: rgba(102, 126, 234, 0.1);
    color: var(--primary-color);
}

.stat-content {
    display: flex;
    flex-direction: column;
}

.stat-number {
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--text-primary);
    line-height: 1;
}

.stat-number.full {
    color: var(--danger-color);
}

.stat-label {
    font-size: 0.75rem;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-icon {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: white;
    margin: 0 auto 2rem;
}

.empty-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 0.5rem 0;
}

.empty-message {
    color: var(--text-secondary);
    margin: 0;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 3rem;
}

/* Dark Mode Specific Styles */
[data-theme="dark"] .event-stats {
    background: rgba(45, 55, 72, 0.5);
    border-color: rgba(255, 255, 255, 0.1);
}

[data-theme="dark"] .title-icon {
    background: rgba(102, 126, 234, 0.2);
}

/* Responsive Design */
@media (max-width: 768px) {
    .events-container {
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
    
    .page-header {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
        text-align: center;
    }
    
    .page-title {
        font-size: 2rem;
        justify-content: center;
    }
    
    .events-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .event-header {
        flex-direction: column;
        gap: 0.75rem;
        align-items: stretch;
    }
    
    .stat-group {
        flex-direction: column;
        gap: 0.75rem;
    }
}

@media (max-width: 480px) {
    .page-title {
        font-size: 1.75rem;
    }
    
    .events-grid {
        gap: 1rem;
    }
    
    .event-card.modern-card {
        padding: 1rem;
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