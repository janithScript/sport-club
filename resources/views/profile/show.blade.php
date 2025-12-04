@extends('layouts.app')

@section('title', 'Profile - Sports Club Management')

@section('head')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
@endsection

@section('content')
<div class="profile-container">
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
                <i class="fas fa-user-circle title-icon"></i>
                My Profile
            </h1>
            <p class="page-subtitle">Manage your personal information and account settings</p>
        </div>
    </div>

    <!-- Profile Content -->
    <div class="profile-content">
        <!-- Profile Sidebar -->
        <div class="profile-sidebar">
            <div class="profile-card modern-card">
                <div class="profile-image-section mt-2">
                    <div class="profile-image-wrapper">
                        @if(auth()->user()->profile_image)
                            <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="Profile Image" class="profile-image">
                        @else
                            <div class="profile-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        @endif
                    </div>
                    <h2 class="profile-name">{{ auth()->user()->name }}</h2>
                    <p class="profile-email">{{ auth()->user()->email }}</p>
                </div>
                
                <div class="profile-nav">
                    <ul class="nav-tabs">
                        <li class="nav-item active" data-tab="overview">
                            <a href="#" class="nav-link">
                                <i class="fas fa-user"></i>
                                <span>Overview</span>
                            </a>
                        </li>
                        <li class="nav-item" data-tab="settings">
                            <a href="#" class="nav-link">
                                <i class="fas fa-cog"></i>
                                <span>Settings</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Profile Main Content -->
        <div class="profile-main">
            <!-- Overview Tab -->
            <div class="tab-content active" id="overview-tab">
                <div class="profile-grid">
                    <!-- Personal Info Card -->
                    <div class="profile-card modern-card">
                        <div class="card-header enhanced-header">
                            <div class="header-content">
                                <h3 class="card-title">
                                    <i class="fas fa-info-circle"></i>
                                    Personal Information
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="info-row">
                                <div class="info-label">Full Name</div>
                                <div class="info-value">{{ auth()->user()->name }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Email Address</div>
                                <div class="info-value">{{ auth()->user()->email }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Phone Number</div>
                                <div class="info-value">{{ auth()->user()->phone ?? 'Not provided' }}</div>
                            </div>
                            <div class="info-row">
                                <div class="info-label">Member Since</div>
                                <div class="info-value">{{ auth()->user()->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- About Me Card -->
                    <div class="profile-card modern-card">
                        <div class="card-header enhanced-header">
                            <div class="header-content">
                                <h3 class="card-title">
                                    <i class="fas fa-user-edit"></i>
                                    About Me
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(auth()->user()->about_me)
                                <p class="about-content">{{ htmlspecialchars(auth()->user()->about_me) }}</p>
                            @else
                                <p class="about-content empty">You haven't added an about me section yet.</p>
                            @endif
                            
                        </div>
                    </div>


                </div>
            </div>

            <!-- Settings Tab -->
            <div class="tab-content" id="settings-tab">
                <div class="profile-card modern-card">
                    <div class="card-header enhanced-header">
                        <div class="header-content">
                            <h3 class="card-title">
                                <i class="fas fa-lock"></i>
                                Account Settings
                            </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="profile-settings-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" id="name" name="name" class="form-control" value="{{ auth()->user()->name }}" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" id="email" name="email" class="form-control" value="{{ auth()->user()->email }}" readonly>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" id="phone" name="phone" class="form-control" value="{{ auth()->user()->phone ?? '' }}">
                            </div>
                            
                            <div class="form-group">
                                <label for="about_me" class="form-label">About Me</label>
                                <textarea id="about_me" name="about_me" class="form-control" rows="4" placeholder="Tell us about yourself...">{{ auth()->user()->about_me ?? '' }}</textarea>
                            </div>
                            
                            <!-- Profile Image Section -->
                            <div class="form-group">
                                <label class="form-label">Profile Image</label>
                                <div class="image-upload-section">
                                    <div class="current-image">
                                        @if(auth()->user()->profile_image)
                                            <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="Profile Image" class="preview-image">
                                        @else
                                            <div class="preview-placeholder">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="upload-controls">
                                        <label for="profile_image" class="btn-modern secondary">
                                            <i class="fas fa-upload"></i>
                                            Choose Image
                                        </label>
                                        <input type="file" id="profile_image" name="profile_image" accept="image/*" style="display: none;">
                                        <button type="submit" class="btn-modern primary" id="upload-image-btn" disabled>
                                            <i class="fas fa-save"></i>
                                            Save Image
                                        </button>
                                        @if(auth()->user()->profile_image)
                                            <button type="button" class="btn-modern danger" id="remove-image-btn">
                                                <i class="fas fa-trash"></i>
                                                Remove Image
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-section-divider">
                                <h4 class="section-title">Change Password</h4>
                                <p class="section-description">Leave blank if you don't want to change your password</p>
                            </div>
                            
                            <div class="form-group">
                                <label for="current_password" class="form-label">Current Password</label>
                                <div class="password-container">
                                    <input type="password" id="current_password" name="current_password" class="form-control">
                                    <span class="toggle-password" onclick="togglePasswordVisibility('current_password')">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="new_password" class="form-label">New Password</label>
                                <div class="password-container">
                                    <input type="password" id="new_password" name="new_password" class="form-control">
                                    <span class="toggle-password" onclick="togglePasswordVisibility('new_password')">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                                <div class="password-container">
                                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control">
                                    <span class="toggle-password" onclick="togglePasswordVisibility('new_password_confirmation')">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn-modern primary">
                                    <i class="fas fa-save"></i>
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
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
    --border-color: #4a5568;
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

.profile-container {
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

/* Profile Layout */
.profile-content {
    display: flex;
    gap: 2rem;
}

.profile-sidebar {
    flex: 0 0 300px;
}

.profile-main {
    flex: 1;
}

/* Profile Card */
.profile-card.modern-card {
    background: var(--card-bg);
    backdrop-filter: var(--backdrop-blur);
    border-radius: 20px;
    padding: 0;
    box-shadow: 0 8px 30px var(--shadow-color);
    border: 1px solid var(--border-color);
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.profile-card.modern-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 50px var(--shadow-color);
}

/* Profile Image Section */
.profile-image-section {
    text-align: center;
    margin-bottom: 2rem;
}

.profile-image-wrapper {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    margin: 0 auto 1rem;
    overflow: hidden;
    border: 3px solid var(--primary-color);
}

.profile-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: white;
}

.profile-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 1rem 0 0.25rem;
}

.profile-email {
    color: var(--text-secondary);
    margin: 0;
}

/* Navigation */
.profile-nav .nav-tabs {
    list-style: none;
    padding: 0;
    margin: 0;
}

.nav-item {
    margin-bottom: 0.5rem;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    border-radius: 12px;
    text-decoration: none;
    color: var(--text-secondary);
    transition: all 0.2s ease;
}

.nav-link:hover,
.nav-item.active .nav-link {
    background: rgba(102, 126, 234, 0.1);
    color: var(--primary-color);
}

.nav-item.active .nav-link {
    font-weight: 600;
}

/* Tab Content */
.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

/* Profile Grid */
.profile-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

/* Card Header */
.card-header {
    padding: 0;
}

.card-header.enhanced-header {
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

/* Card Body */
.card-body {
    padding: 1rem;
}

/* Info Rows */
.info-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border-color);
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: var(--text-primary);
}

.info-value {
    color: var(--text-secondary);
}

/* Improved contrast for form elements in dark mode */
[data-theme="dark"] .form-label {
    color: #e2e8f0;
}

[data-theme="dark"] .form-control {
    color: #f7fafc;
}

/* About Content */
.about-content {
    color: var(--text-secondary);
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.about-content.empty {
    font-style: italic;
    color: var(--text-secondary);
}

/* Image Upload Section */
.image-upload-section {
    text-align: center;
}

.current-image {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    margin: 0 auto 1.5rem;
    overflow: hidden;
    border: 2px dashed var(--border-color);
    display: flex;
    align-items: center;
    justify-content: center;
}

.preview-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.preview-placeholder {
    width: 100%;
    height: 100%;
    background: rgba(102, 126, 234, 0.05);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: var(--primary-color);
}

.upload-controls {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
}

/* Form Elements */
.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.form-control {
    background: transparent;
    border: 1px solid var(--border-color);
    padding: 0.875rem 1rem;
    border-radius: 12px;
    font-size: 1rem;
    color: var(--text-primary);
    transition: all 0.2s ease;
    width: 100%;
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.05);
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
}

/* Enhanced visibility for dark mode inputs */
[data-theme="dark"] .form-control {
    background: rgba(26, 32, 44, 0.3);
    border: 1px solid var(--border-color);
}

.form-section-divider {
    margin: 2rem 0;
    padding: 1.5rem 0;
    border-top: 1px solid var(--border-color);
    border-bottom: 1px solid var(--border-color);
}

.section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 0.5rem;
}

.section-description {
    color: var(--text-secondary);
    margin: 0;
}

/* Password Container */
.password-container {
    position: relative;
}

.toggle-password {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    color: var(--text-secondary);
}

.toggle-password:hover {
    color: var(--primary-color);
}

.form-control {
    padding-right: 40px;
}

/* Form Actions */
.form-actions {
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-color);
    text-align: right;
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

.btn-modern.secondary {
    background: rgba(102, 126, 234, 0.1);
    color: var(--primary-color);
    border: 1px solid rgba(102, 126, 234, 0.3);
}

.btn-modern.danger {
    background: rgba(245, 101, 101, 0.1);
    color: var(--danger-color);
    border: 1px solid rgba(245, 101, 101, 0.3);
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    text-decoration: none;
    color: white;
}

.btn-modern.secondary:hover {
    color: var(--primary-color);
    background: rgba(102, 126, 234, 0.2);
}

.btn-modern.danger:hover {
    color: var(--danger-color);
    background: rgba(245, 101, 101, 0.2);
}

.btn-modern:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Dark Mode Specific Styles */
[data-theme="dark"] .title-icon {
    background: rgba(102, 126, 234, 0.2);
}

[data-theme="dark"] .profile-image-wrapper {
    border-color: var(--primary-color);
}

[data-theme="dark"] .preview-placeholder {
    background: rgba(45, 55, 72, 0.5);
}
[data-theme="dark"] .card-header.enhanced-header {
    background: linear-gradient(135deg, rgba(1, 21, 28, 0.8), rgba(255, 255, 255, 0.5));
}
/* Enhanced form control visibility */
.form-control::-webkit-input-placeholder {
    color: var(--text-secondary);
    opacity: 0.7;
}

.form-control::-moz-placeholder {
    color: var(--text-secondary);
    opacity: 0.7;
}

.form-control:-ms-input-placeholder {
    color: var(--text-secondary);
    opacity: 0.7;
}

.form-control::-ms-input-placeholder {
    color: var(--text-secondary);
    opacity: 0.7;
}

.form-control::placeholder {
    color: var(--text-secondary);
    opacity: 0.7;
}

/* Responsive Design */
@media (max-width: 992px) {
    .profile-content {
        flex-direction: column;
    }
    
    .profile-sidebar {
        flex: 0 0 auto;
    }
    
    .profile-card.modern-card {
        margin-bottom: 1.5rem;
    }
    
    .profile-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .profile-container {
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
    
    .upload-controls {
        flex-direction: column;
        align-items: center;
    }
    
    .form-actions {
        text-align: center;
    }
    
    .info-row {
        flex-direction: column;
        gap: 0.25rem;
    }
}

@media (max-width: 480px) {
    .page-title {
        font-size: 1.75rem;
    }
    
    .profile-card.modern-card {
        padding: 1rem;
    }
    
    .profile-image-wrapper {
        width: 100px;
        height: 100px;
    }
    
    .current-image {
        width: 120px;
        height: 120px;
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
    
    // Tab navigation
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all items
            navItems.forEach(nav => nav.classList.remove('active'));
            
            // Add active class to clicked item
            this.classList.add('active');
            
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Show corresponding tab content
            const tabId = this.getAttribute('data-tab') + '-tab';
            document.getElementById(tabId).classList.add('active');
        });
    });
    
    // Profile image upload
    const profileImageInput = document.getElementById('profile_image');
    const uploadImageButton = document.getElementById('upload-image-btn');
    
    if (profileImageInput) {
        profileImageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                uploadImageButton.disabled = false;
                
                // Preview image
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.querySelector('.current-image');
                    if (preview) {
                        preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="preview-image">`;
                    }
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
    
    // Remove image button
    const removeImageButton = document.getElementById('remove-image-btn');
    if (removeImageButton) {
        removeImageButton.addEventListener('click', function() {
            if (confirm('Are you sure you want to remove your profile image?')) {
                // Create a form to submit the removal request
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("profile.update") }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'PUT';
                
                const removeField = document.createElement('input');
                removeField.type = 'hidden';
                removeField.name = 'remove_profile_image';
                removeField.value = '1';
                
                form.appendChild(csrfToken);
                form.appendChild(methodField);
                form.appendChild(removeField);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
    
    // Edit about me button
    const editAboutButton = document.getElementById('edit-about-btn');
    if (editAboutButton) {
        editAboutButton.addEventListener('click', function() {
            // Switch to settings tab
            navItems.forEach(nav => nav.classList.remove('active'));
            document.querySelector('[data-tab="settings"]').classList.add('active');
            
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            document.getElementById('settings-tab').classList.add('active');
            
            // Scroll to about me section
            const aboutSection = document.querySelector('[name="about_me"]');
            if (aboutSection) {
                aboutSection.scrollIntoView({ behavior: 'smooth' });
                aboutSection.focus();
            }
        });
    }
});

// Password visibility toggle function
function togglePasswordVisibility(fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const toggleIcon = passwordInput.parentElement.querySelector('.toggle-password i');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}
</script>
@endsection