@extends('layouts.app')

@section('title', 'Edit Reservation - Sports Club Management')

@section('head')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
@endsection

@section('content')
<div class="equipment-container">
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
                <i class="fas fa-edit title-icon"></i>
                Edit Equipment Reservation
            </h1>
            <p class="page-subtitle">Modify your equipment reservation details</p>
        </div>
    </div>

    <div class="reservation-edit-card modern-card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-dumbbell"></i>
                {{ $reservation->equipment->name }}
            </h3>
            <div class="reservation-status">
                <span class="status-badge reserved">Reserved</span>
            </div>
        </div>

        <form action="{{ route('equipment.reservations.update', $reservation) }}" method="POST" class="reservation-form">
            @csrf
            @method('PUT')
            
            <div class="card-body">
                <div class="form-group">
                    <label for="quantity" class="form-label">
                        <i class="fas fa-sort-numeric-up"></i>
                        Quantity
                    </label>
                    <select name="quantity" class="form-control modern-select" required>
                        @for($i = 1; $i <= min($reservation->equipment->available_quantity + $reservation->quantity, 10); $i++)
                            <option value="{{ $i }}" {{ $reservation->quantity == $i ? 'selected' : '' }}>
                                {{ $i }} item{{ $i > 1 ? 's' : '' }}
                            </option>
                        @endfor
                    </select>
                    <small class="form-hint">
                        Maximum {{ min($reservation->equipment->available_quantity + $reservation->quantity, 10) }} items can be reserved
                    </small>
                </div>

                <div class="form-group">
                    <label for="reserved_from" class="form-label">
                        <i class="fas fa-calendar-day"></i>
                        From Date & Time
                    </label>
                    <input type="datetime-local" 
                           name="reserved_from" 
                           class="form-control modern-input" 
                           value="{{ $reservation->reserved_from->format('Y-m-d\TH:i') }}"
                           required>
                </div>

                <div class="form-group">
                    <label for="reserved_to" class="form-label">
                        <i class="fas fa-calendar-check"></i>
                        To Date & Time
                    </label>
                    <input type="datetime-local" 
                           name="reserved_to" 
                           class="form-control modern-input" 
                           value="{{ $reservation->reserved_to->format('Y-m-d\TH:i') }}"
                           required>
                </div>

                <div class="reservation-summary">
                    <div class="summary-item">
                        <i class="fas fa-info-circle"></i>
                        <span>You can reserve this equipment for up to 7 days</span>
                    </div>
                    <div class="summary-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Current reservation: {{ $reservation->reserved_from->format('M d, Y g:i A') }} - {{ $reservation->reserved_to->format('M d, Y g:i A') }}</span>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <a href="{{ route('dashboard.index') }}" class="btn-modern outline">
                    <i class="fas fa-arrow-left"></i>
                    Back to Dashboard
                </a>
                <div class="form-actions">
                    <button type="submit" class="btn-modern success">
                        <i class="fas fa-save"></i>
                        Update Reservation
                    </button>
                </div>
            </div>
        </form>
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

.equipment-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.page-header {
    margin-bottom: 2rem;
    text-align: center;
}

.header-content {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    padding: 2rem;
    border-radius: 15px;
    color: white;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    position: relative;
    overflow: hidden;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}

.title-icon {
    font-size: 2rem;
}

.page-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    font-weight: 400;
}

.modern-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: var(--backdrop-blur);
    border-radius: 15px;
    border: 1px solid var(--border-color);
    box-shadow: 0 8px 32px var(--shadow-color);
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
}

.card-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
}

.card-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    text-transform: uppercase;
}

.status-badge.reserved {
    background: rgba(237, 137, 54, 0.15);
    color: var(--warning-color);
}

.card-body {
    padding: 2rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.9);
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.modern-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
}

.form-hint {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.reservation-summary {
    background: rgba(102, 126, 234, 0.05);
    border-radius: 8px;
    padding: 1rem;
    margin-top: 1rem;
}

.summary-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    color: var(--text-secondary);
}

.summary-item:last-child {
    margin-bottom: 0;
}

.card-footer {
    padding: 1.5rem;
    border-top: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(247, 250, 252, 0.5);
}

.btn-modern {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    font-size: 0.95rem;
    position: relative;
    overflow: hidden;
}

.btn-modern.success {
    background: linear-gradient(135deg, var(--success-color) 0%, #38a169 100%);
    color: white;
}

.btn-modern.outline {
    background: transparent;
    border: 1px solid var(--border-color);
    color: var(--text-primary);
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-modern:active {
    transform: translateY(0);
}

.form-actions {
    display: flex;
    gap: 0.75rem;
}

.theme-toggle-wrapper {
    position: fixed;
    top: 1rem;
    right: 1rem;
    z-index: 1000;
}

.theme-toggle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid var(--border-color);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px var(--shadow-color);
}

.theme-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 15px var(--shadow-color);
}

[data-theme="dark"] .theme-toggle {
    background: rgba(45, 55, 72, 0.9);
    color: #f7fafc;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .equipment-container {
        padding: 1rem;
    }
    
    .header-content {
        padding: 1.5rem;
    }
    
    .page-title {
        font-size: 2rem;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .card-footer {
        flex-direction: column;
        gap: 1rem;
    }
    
    .form-actions {
        width: 100%;
        justify-content: center;
    }
}

/* Dark mode adjustments */
[data-theme="dark"] .modern-card {
    background: rgba(45, 55, 72, 0.95);
    border-color: rgba(255, 255, 255, 0.1);
}

[data-theme="dark"] .card-header {
    border-color: rgba(255, 255, 255, 0.1);
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
}

[data-theme="dark"] .form-control {
    background: rgba(26, 32, 44, 0.9);
    border-color: var(--border-color);
    color: var(--text-primary);
}

[data-theme="dark"] .card-footer {
    border-color: rgba(255, 255, 255, 0.1);
    background: rgba(26, 32, 44, 0.5);
}

[data-theme="dark"] .reservation-summary {
    background: rgba(102, 126, 234, 0.1);
}
</style>
@endsection