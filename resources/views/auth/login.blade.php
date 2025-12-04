@extends('layouts.app')

@section('title', 'Login - Sports Club Management')

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

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 mt-5">
            <div class="card modern-card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <div class="password-container">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                    <span class="toggle-password" onclick="togglePasswordVisibility()">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn-modern primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn-modern link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif

                                <a class="btn-modern link" href="{{ route('register') }}">
                                    {{ __('Don\'t have an account? Register') }}
                                </a>
                            </div>
                        </div>
                    </form>
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

/* Modern Card */
.modern-card {
    background: var(--card-bg);
    backdrop-filter: var(--backdrop-blur);
    border-radius: 20px;
    box-shadow: 0 8px 30px var(--shadow-color);
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
}

.card-header {
    font-weight: 700;
    font-size: 1.25rem;
    padding: 1.25rem 1.5rem;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    border-radius: 20px 20px 0 0;
    border-bottom: none;
}

.card-body {
    padding: 1.5rem;
}

.form-control {
    background: transparent;
    border: 1px solid var(--border-color);
    padding: 0.625rem 0.875rem;
    border-radius: 12px;
    font-size: 1rem;
    color: var(--text-primary);
    transition: all 0.2s ease;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.2);
}

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

.form-check-input:checked {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.invalid-feedback {
    display: block;
    color: var(--danger-color);
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

/* Modern Buttons */
.btn-modern {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
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
    box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
}

.btn-modern.link {
    background: transparent;
    color: var(--primary-color);
    padding: 0.5rem;
    font-size: 0.875rem;
    text-decoration: underline;
    box-shadow: none;
    border-radius: 6px;
}

.btn-modern.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(102, 126, 234, 0.4);
}

.btn-modern.link:hover {
    background: rgba(102, 126, 234, 0.05);
    text-decoration: none;
}

[data-theme="dark"] .btn-modern.link {
    color: #90cdf4;
}

[data-theme="dark"] .btn-modern.link:hover {
    background: rgba(102, 126, 234, 0.15);
}
[data-theme="dark"] .form-check-input:checked {
    border-color: var(--light-color) ;
    background-color: var(--light-color);
}
[data-theme="dark"] .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.3);
}

/* Responsive */
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

    .card-header {
        font-size: 1.1rem;
    }

    .btn-modern {
        padding: 0.625rem 1rem;
        font-size: 0.875rem;
    }

    .row.mb-0 .col-md-8 {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .btn-modern.primary,
    .btn-modern.link {
        justify-content: center;
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

function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.querySelector('.toggle-password i');
    
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