@extends('layouts.app')

@section('title', 'Home - Sports Club Management')

@section('head')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
@endsection

@section('content')
<!-- Theme Toggle Button -->
<div class="theme-toggle-wrapper">
    <button class="theme-toggle" id="themeToggle">
        <i class="fas fa-moon"></i>
    </button>
</div>

<!-- Hero Section -->
<div class="hero">
    <div class="hero-background">
        <div class="animated-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
    </div>
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Welcome to Sports Club</h1>
            <p class="hero-subtitle">Join our vibrant community and participate in exciting sports events!</p>
            <div class="hero-buttons">
                @guest
                    <a href="{{ route('register') }}" class="btn-modern primary large">
                        <i class="fas fa-user-plus"></i>
                        <span>Join Now</span>
                    </a>
                    <a href="{{ route('events.index') }}" class="btn-modern secondary large">
                        <i class="fas fa-calendar-alt"></i>
                        <span>View Events</span>
                    </a>
                @else
                    <a href="{{ route('dashboard.index') }}" class="btn-modern primary large">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>My Dashboard</span>
                    </a>
                    <a href="{{ route('events.index') }}" class="btn-modern secondary large">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Explore Events</span>
                    </a>
                @endguest
            </div>
        </div>
    </div>
</div>

<!-- Statistics Section -->
<div class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card modern-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-number counter" data-target="{{ \App\Models\Event::count() }}">0</span>
                    <span class="stat-label">Total Events</span>
                </div>
            </div>
            <div class="stat-card modern-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-number counter" data-target="{{ \App\Models\User::where('is_admin', false)->count() }}">0</span>
                    <span class="stat-label">Active Members</span>
                </div>
            </div>
            <div class="stat-card modern-card">
                <div class="stat-icon">
                    <i class="fas fa-dumbbell"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-number counter" data-target="{{ \App\Models\Equipment::sum('total_quantity') }}">0</span>
                    <span class="stat-label">Equipment Items</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upcoming Events -->
@if($upcomingEvents->count() > 0)
<div class="upcoming-events-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-calendar-alt title-icon"></i>
                Upcoming Events
            </h2>
            <p class="section-subtitle">Don't miss out on these exciting opportunities</p>
        </div>
        <div class="events-grid">
            @foreach($upcomingEvents as $event)
                <div class="event-card modern-card">
                    <div class="event-status-indicator upcoming"></div>
                    
                    <div class="event-header">
                        <h3 class="event-title">{{ $event->title }}</h3>
                        <div class="event-badge">
                            <span class="badge upcoming">
                                <i class="fas fa-calendar-check"></i> Upcoming
                            </span>
                        </div>
                    </div>

                    <div class="event-meta">
                        <div class="meta-item">
                            <i class="fas fa-calendar-day"></i>
                            <span>{{ $event->start_at->format('M d, Y') }}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-clock"></i>
                            <span>{{ $event->start_at->format('g:i A') }}</span>
                        </div>
                        @if($event->location)
                            <div class="meta-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $event->location }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="event-description">
                        <p>{{ Str::limit($event->description, 100) }}</p>
                    </div>
                    
                    @if($event->capacity > 0)
                        <div class="event-stats">
                            <div class="stat-item">
                                <span class="stat-icon available">
                                    <i class="fas fa-users"></i>
                                </span>
                                <div class="stat-info">
                                    <span class="stat-value">{{ $event->available_spots }}</span>
                                    <span class="stat-text">spots available</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="event-actions">
                        <a href="{{ route('events.show', $event) }}" class="btn-modern primary full-width">
                            <span>View Details</span>
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="section-footer">
            <a href="{{ route('events.index') }}" class="btn-modern primary large">
                <span>View All Events</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
@endif

<!-- Features Section -->
<div class="features-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">
                <i class="fas fa-star title-icon"></i>
                Why Choose Our Sports Club?
            </h2>
            <p class="section-subtitle">Experience the best sports management platform</p>
        </div>
        <div class="features-grid">
            <div class="feature-card modern-card">
                <div class="feature-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="feature-content">
                    <h3 class="feature-title">Event Management</h3>
                    <p class="feature-description">Discover and register for exciting sports events. Stay updated with schedules and never miss an opportunity to participate.</p>
                </div>
            </div>
            
            <div class="feature-card modern-card">
                <div class="feature-icon">
                    <i class="fas fa-football-ball"></i>
                </div>
                <div class="feature-content">
                    <h3 class="feature-title">Equipment Booking</h3>
                    <p class="feature-description">Reserve sports equipment for your activities. Check availability and book equipment for your preferred time slots.</p>
                </div>
            </div>
            
            <div class="feature-card modern-card">
                <div class="feature-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <div class="feature-content">
                    <h3 class="feature-title">Communication</h3>
                    <p class="feature-description">Stay connected with club administrators and other members through our integrated messaging system.</p>
                </div>
            </div>
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

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 2rem;
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

/* Hero Section */
.hero {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    padding: 6rem 0 4rem;
    position: relative;
    overflow: hidden;
    margin-bottom: 4rem;
}

.hero-background {
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
    width: 150px;
    height: 150px;
    top: 20%;
    left: 10%;
    animation-delay: 0s;
}

.shape-2 {
    width: 100px;
    height: 100px;
    top: 60%;
    right: 15%;
    animation-delay: 2s;
}

.shape-3 {
    width: 120px;
    height: 120px;
    bottom: 20%;
    left: 60%;
    animation-delay: 4s;
}

@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-30px) rotate(180deg); }
}

.hero-content {
    text-align: center;
    position: relative;
    z-index: 2;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 1rem;
    line-height: 1.2;
}

.hero-subtitle {
    font-size: 1.3rem;
    margin-bottom: 2.5rem;
    opacity: 0.95;
    font-weight: 400;
}

.hero-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

/* Modern Buttons */
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
    background: white;
    color: var(--primary-color);
    box-shadow: 0 4px 15px rgba(255, 255, 255, 0.3);
}

.btn-modern.secondary {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 2px solid white;
}

.btn-modern.large {
    padding: 1rem 2rem;
    font-size: 1rem;
}

.btn-modern.full-width {
    width: 100%;
    justify-content: center;
}

.btn-modern.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 255, 255, 0.4);
    text-decoration: none;
    color: var(--primary-color);
}

.btn-modern.secondary:hover {
    background: white;
    color: var(--primary-color);
    transform: translateY(-2px);
    text-decoration: none;
}

/* Statistics Section */
.stats-section {
    padding: 3rem 0;
    margin-bottom: 4rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
}

.stat-card.modern-card {
    background: var(--card-bg);
    backdrop-filter: var(--backdrop-blur);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 8px 30px var(--shadow-color);
    border: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 1.5rem;
    transition: all 0.3s ease;
}

.stat-card.modern-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 50px var(--shadow-color);
}

.stat-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    color: white;
}

.stat-content {
    flex: 1;
}

.stat-number {
    display: block;
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--text-primary);
    line-height: 1;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 1rem;
    color: var(--text-secondary);
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Section Headers */
.section-header {
    text-align: center;
    margin-bottom: 3rem;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--text-primary);
    margin: 0 0 0.5rem 0;
    display: flex;
    align-items: center;
    justify-content: center;
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

[data-theme="dark"] .title-icon {
    background: rgba(102, 126, 234, 0.2);
}

.section-subtitle {
    font-size: 1.1rem;
    color: var(--text-secondary);
    margin: 0;
    font-weight: 400;
}

/* Upcoming Events Section */
.upcoming-events-section {
    padding: 3rem 0;
    margin-bottom: 4rem;
}

.events-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

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

[data-theme="dark"] .event-stats {
    background: rgba(45, 55, 72, 0.5);
    border-color: rgba(255, 255, 255, 0.1);
}
[data-theme="dark"] .hero {
    background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
}
[data-theme="dark"] .btn-modern.primary {
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
}
.stat-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.stat-item .stat-icon {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
}

.stat-item .stat-icon.available {
    background: rgba(72, 187, 120, 0.1);
    color: var(--success-color);
}

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-value {
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--text-primary);
    line-height: 1;
}

.stat-text {
    font-size: 0.75rem;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.section-footer {
    text-align: center;
}

/* Features Section */
.features-section {
    padding: 3rem 0 5rem;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 2rem;
}

.feature-card.modern-card {
    background: var(--card-bg);
    backdrop-filter: var(--backdrop-blur);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 8px 30px var(--shadow-color);
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
}

.feature-card.modern-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 50px var(--shadow-color);
}

.feature-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    color: white;
    margin-bottom: 1.5rem;
}

.feature-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 1rem 0;
}

.feature-description {
    color: var(--text-secondary);
    margin: 0;
    line-height: 1.6;
}

/* Responsive Design */
@media (max-width: 768px) {
    .container {
        padding: 0 1rem;
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
    
    .hero {
        padding: 4rem 0 3rem;
    }
    
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
    }
    
    .section-title {
        font-size: 2rem;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .stats-grid,
    .events-grid,
    .features-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .stat-card.modern-card {
        flex-direction: column;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-subtitle {
        font-size: 1rem;
    }
    
    .hero-buttons {
        flex-direction: column;
        align-items: stretch;
    }
    
    .btn-modern.large {
        width: 100%;
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
    
    // Counter animation
    function animateCounters() {
        const counters = document.querySelectorAll('.counter[data-target]');
        counters.forEach(counter => {
            const target = parseInt(counter.dataset.target) || 0;
            const duration = 2000;
            const increment = target / (duration / 16);
            let current = 0;
            
            const updateCounter = () => {
                current += increment;
                if (current < target) {
                    counter.textContent = Math.floor(current);
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = target;
                }
            };
            
            updateCounter();
        });
    }
    
    // Observe counters for animation
    const observerOptions = {
        threshold: 0.5
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounters();
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    const statsSection = document.querySelector('.stats-section');
    if (statsSection) {
        observer.observe(statsSection);
    }
});
</script>
@endsection