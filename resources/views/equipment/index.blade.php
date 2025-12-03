@extends('layouts.app')

@section('title', 'Equipment - Sports Club Management')

@section('head')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection

@section('content')
<div class="equipment-container" x-data="{ showModal: false, selectedEquipment: null }">
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
                <i class="fas fa-dumbbell title-icon"></i>
                Sports Equipment
            </h1>
            <p class="page-subtitle">Reserve equipment for your sports activities</p>
        </div>
    </div>

    @if($equipment->count() > 0)
        <div class="equipment-grid">
            @foreach($equipment as $item)
                <div class="equipment-card modern-card">
                    <!-- Equipment Status Indicator -->
                    <div class="equipment-status-indicator @if($item->available_quantity > 5) available @elseif($item->available_quantity > 0) low @else unavailable @endif"></div>
                    
                    <div class="equipment-header">
                        <h3 class="equipment-title">{{ $item->name }}</h3>
                        @if($item->category)
                            <div class="category-badge">
                                <span class="badge category">
                                    <i class="fas fa-tag"></i> {{ $item->category }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="equipment-stats">
                        <div class="stat-group">
                            <div class="stat-item total">
                                <div class="stat-icon">
                                    <i class="fas fa-boxes"></i>
                                </div>
                                <div class="stat-content">
                                    <span class="stat-number">{{ $item->total_quantity }}</span>
                                    <span class="stat-label">Total Quantity</span>
                                </div>
                            </div>
                            
                            <div class="stat-item available">
                                <div class="stat-icon availability-status 
                                    @if($item->available_quantity > 5) available
                                    @elseif($item->available_quantity > 0) low
                                    @else unavailable @endif">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stat-content">
                                    <span class="stat-number availability-number
                                        @if($item->available_quantity > 5) available
                                        @elseif($item->available_quantity > 0) low
                                        @else unavailable @endif">
                                        {{ $item->available_quantity }}
                                    </span>
                                    <span class="stat-label">Available</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="equipment-details">
                        @if($item->condition)
                            <div class="detail-item">
                                <i class="fas fa-tools"></i>
                                <span class="detail-label">Condition:</span>
                                <span class="detail-value">{{ ucfirst($item->condition) }}</span>
                            </div>
                        @endif

                        @if($item->asset_tag)
                            <div class="detail-item">
                                <i class="fas fa-barcode"></i>
                                <span class="detail-label">Asset Tag:</span>
                                <span class="detail-value">{{ $item->asset_tag }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="equipment-actions">
                        @if($item->available_quantity > 0)
                            <button type="button" 
                                    class="btn-modern success full-width" 
                                    @click="selectedEquipment = { id: {{ $item->id }}, available_quantity: {{ $item->available_quantity }} }; showModal = true">
                                <i class="fas fa-calendar-plus"></i>
                                <span>Reserve Equipment</span>
                            </button>
                        @else
                            <div class="unavailable-message">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>Currently Unavailable</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Modals -->
        @auth
            <div class="modal-overlay" x-show="showModal" x-transition style="z-index: 10000; display: flex;">
                <div class="modal-container" @click.away="showModal = false">
                    <div class="modal-header">
                        <h3 class="modal-title">
                            <i class="fas fa-calendar-plus"></i>
                            Reserve Equipment
                        </h3>
                        <button type="button" class="modal-close" @click="showModal = false">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <form action="{{ route('equipment.reserve') }}" method="POST" class="reservation-form">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="equipment_id" :value="selectedEquipment?.id">
                            
                            <div class="form-group">
                                <label for="quantity" class="form-label">
                                    <i class="fas fa-sort-numeric-up"></i>
                                    Quantity
                                </label>
                                <select name="quantity" class="form-control modern-select" required>
                                    <option value="1">1 item</option>
                                    <option value="2">2 items</option>
                                    <option value="3">3 items</option>
                                    <option value="4">4 items</option>
                                    <option value="5">5 items</option>
                                </select>
                                <small class="form-hint">Maximum <span x-text="Math.min(selectedEquipment?.available_quantity || 1, 5)"></span> items can be reserved</small>
                            </div>

                            <div class="form-group">
                                <label for="reserved_from" class="form-label">
                                    <i class="fas fa-calendar-day"></i>
                                    From Date & Time
                                </label>
                                <input type="datetime-local" 
                                       name="reserved_from" 
                                       class="form-control modern-input" 
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
                                       required>
                            </div>

                            <div class="reservation-summary">
                                <div class="summary-item">
                                    <i class="fas fa-info-circle"></i>
                                    <span>You can reserve this equipment for up to 7 days</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn-modern outline" @click="showModal = false">
                                <i class="fas fa-times"></i>
                                Cancel
                            </button>
                            <button type="submit" class="btn-modern success">
                                <i class="fas fa-check"></i>
                                Confirm Reservation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endauth

        <!-- Pagination -->
        <div class="pagination-wrapper">
            {{ $equipment->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-dumbbell"></i>
            </div>
            <h3 class="empty-title">No equipment available</h3>
            <p class="empty-message">Check back later for available equipment!</p>
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
    --available-color: #48bb78;
    --low-color: #ed8936;
    --unavailable-color: #f56565;
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

.equipment-container {
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
    margin-bottom: 3rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--border-color);
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

/* Equipment Grid */
.equipment-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

/* Equipment Cards */
.equipment-card.modern-card {
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

.equipment-card.modern-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 50px var(--shadow-color);
}

.equipment-status-indicator {
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    border-radius: 0 4px 4px 0;
}

.equipment-status-indicator.available {
    background: var(--available-color);
}

.equipment-status-indicator.low {
    background: var(--low-color);
}

.equipment-status-indicator.unavailable {
    background: var(--unavailable-color);
}

.equipment-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
}

.equipment-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
    flex: 1;
    line-height: 1.3;
}

.category-badge .badge {
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

.badge.category {
    background: rgba(102, 126, 234, 0.1);
    color: var(--primary-color);
}

.equipment-stats {
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: rgba(102, 126, 234, 0.05);
    border-radius: 12px;
    border: 1px solid rgba(102, 126, 234, 0.1);
}

.stat-group {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.stat-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.stat-item.total .stat-icon {
    background: rgba(102, 126, 234, 0.1);
    color: var(--primary-color);
}

.stat-icon.availability-status.available {
    background: rgba(72, 187, 120, 0.1);
    color: var(--available-color);
}

.stat-icon.availability-status.low {
    background: rgba(237, 137, 54, 0.1);
    color: var(--low-color);
}

.stat-icon.availability-status.unavailable {
    background: rgba(245, 101, 101, 0.1);
    color: var(--unavailable-color);
}

.stat-content {
    display: flex;
    flex-direction: column;
}

.stat-number {
    font-weight: 700;
    font-size: 1.25rem;
    color: var(--text-primary);
    line-height: 1;
}

.stat-number.availability-number.available {
    color: var(--available-color);
}

.stat-number.availability-number.low {
    color: var(--low-color);
}

.stat-number.availability-number.unavailable {
    color: var(--unavailable-color);
}

.stat-label {
    font-size: 0.75rem;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 500;
}

.equipment-details {
    margin-bottom: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.detail-item i {
    width: 16px;
    color: var(--primary-color);
}

.detail-label {
    font-weight: 500;
}

.detail-value {
    color: var(--text-primary);
    font-weight: 600;
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

.btn-modern.success {
    background: linear-gradient(135deg, var(--success-color), #38a169);
    color: white;
    box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
}

.btn-modern.outline {
    background: transparent;
    border: 1.5px solid var(--text-secondary);
    color: var(--text-secondary);
}

.btn-modern.success:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(72, 187, 120, 0.4);
}

.btn-modern.full-width {
    width: 100%;
    justify-content: center;
}

.unavailable-message {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.875rem 1.5rem;
    background: rgba(245, 101, 101, 0.1);
    color: var(--danger-color);
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.875rem;
}

/* Enhanced Modal */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(5px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    padding: 1rem;
}

.modal-container {
    background: var(--card-bg);
    backdrop-filter: var(--backdrop-blur);
    border-radius: 20px;
    width: 100%;
    max-width: 500px;
    max-height: 90vh;
    box-shadow: 0 25px 50px var(--shadow-color);
    border: 1px solid var(--border-color);
    display: flex;
    flex-direction: column;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), transparent);
}

.modal-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.modal-close {
    width: 35px;
    height: 35px;
    border: none;
    background: rgba(0, 0, 0, 0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.2s ease;
}

.modal-close:hover {
    background: var(--danger-color);
    color: white;
}

.modal-body {
    padding: 1.5rem;
    overflow-y: auto;
    flex: 1;
}

.reservation-form {
    display: flex;
    flex-direction: column;
    flex: 1 1 auto;
    min-height: 0;
    overflow: hidden;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.form-control.modern-select,
.form-control.modern-input {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    font-size: 0.875rem;
    color: var(--text-primary);
    background: var(--card-bg);
    transition: all 0.2s ease;
}

.form-control.modern-select:focus,
.form-control.modern-input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-hint {
    font-size: 0.75rem;
    color: var(--text-secondary);
    margin-top: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.reservation-summary {
    background: rgba(66, 153, 225, 0.1);
    border: 1px solid rgba(66, 153, 225, 0.2);
    border-radius: 12px;
    padding: 1rem;
    margin-top: 1rem;
}

.summary-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: var(--info-color);
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    padding: 1.5rem;
    border-top: 1px solid var(--border-color);
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.02), transparent);
    flex-shrink: 0;
    z-index: 10;
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
[data-theme="dark"] .equipment-stats,
[data-theme="dark"] .reservation-summary {
    background: rgba(45, 55, 72, 0.5);
    border-color: rgba(255, 255, 255, 0.1);
}

[data-theme="dark"] .title-icon {
    background: rgba(102, 126, 234, 0.2);
}

[data-theme="dark"] .modal-header,
[data-theme="dark"] .modal-footer {
    background: linear-gradient(135deg, rgba(45, 55, 72, 0.5), transparent);
}

/* Responsive Design */
@media (max-width: 768px) {
    .equipment-container {
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
    
    .page-title {
        font-size: 2rem;
    }
    
    .equipment-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .equipment-header {
        flex-direction: column;
        gap: 0.75rem;
        align-items: stretch;
    }
    
    .stat-group {
        grid-template-columns: 1fr;
    }
    
    .modal-container {
        margin: 1rem;
        max-height: calc(100vh - 2rem);
    }
    
    .modal-footer {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .page-title {
        font-size: 1.75rem;
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
    
    .equipment-grid {
        gap: 1rem;
    }
    
    .equipment-card.modern-card {
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

    // Date validation for reservation form
    document.querySelectorAll('input[name="reserved_from"]').forEach(fromInput => {
        fromInput.addEventListener('change', function() {
            const toInput = this.closest('.modal-body').querySelector('input[name="reserved_to"]');
            const fromDate = new Date(this.value);
            const maxDate = new Date(fromDate);
            maxDate.setDate(maxDate.getDate() + 7); // Max 7 days reservation
            
            toInput.min = this.value;
            toInput.max = maxDate.toISOString().slice(0, 16);
            
            // If to date is before from date, clear it
            if (toInput.value && new Date(toInput.value) <= fromDate) {
                toInput.value = '';
            }
        });
    });

    document.querySelectorAll('input[name="reserved_to"]').forEach(toInput => {
        toInput.addEventListener('change', function() {
            const fromInput = this.closest('.modal-body').querySelector('input[name="reserved_from"]');
            
            if (fromInput.value && new Date(this.value) <= new Date(fromInput.value)) {
                alert('End date must be after start date');
                this.value = '';
            }
        });
    });
});
</script>
@endsection