<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sports Club')</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body>
    <div id="app" x-data="{ mobileMenuOpen: false }">
        <!-- Mobile Header and Navigation Container -->
        <div class="mobile-container">
            <!-- Mobile Menu Toggle Button -->
            <!-- Mobile Header -->
            <div class="mobile-header">
                <div class="container">
                    <!-- Menu Toggle - Left Corner -->
                    <button class="menu-toggle" @click="mobileMenuOpen = !mobileMenuOpen">
                        <i :class="mobileMenuOpen ? 'fas fa-times' : 'fas fa-bars'" 
                        style="transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);"></i>
                    </button>
                    
                    <!-- Brand - Center Left -->
                    <a href="{{ route('home') }}" class="navbar-brand">
                        <i class="fas fa-trophy me-2"></i>Sports Club
                    </a>
                    
                    <!-- Notification Bell - Right Corner -->
                    <a href="#" class="notification-bell">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </a>
                </div>
            </div>

            
            <!-- Mobile Navigation -->
            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95">
                <ul class="navbar-nav mobile-nav">
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                    <li><a href="{{ route('events.index') }}" class="{{ request()->routeIs('events.index') || request()->routeIs('events.show') ? 'active' : '' }}">Events</a></li>
                    
                    @auth
                        <li><a href="{{ route('dashboard.index') }}" class="{{ request()->routeIs('dashboard.index') ? 'active' : '' }}">Dashboard</a></li>
                        <li><a href="{{ route('equipment.index') }}" class="{{ request()->routeIs('equipment.index') || request()->routeIs('equipment.show') ? 'active' : '' }}">Equipment</a></li>
                        <li><a href="{{ route('messages.index') }}" class="{{ request()->routeIs('messages.index') || request()->routeIs('messages.show') || request()->routeIs('messages.conversation') ? 'active' : '' }}">Messages</a></li>
                        <li><a href="{{ route('profile.show') }}" class="{{ request()->routeIs('profile.show') ? 'active' : '' }}">Profile</a></li>
                        @if(auth()->user()->is_admin)
                            <li><a href="/admin" target="_blank">Admin Panel</a></li>
                        @endif
                        
                        <li class="nav-item-mobile">
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-secondary btn-sm">
                                    Logout
                                </button>
                            </form>
                        </li>
                        
                    @else
                        <li><a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'active' : '' }}">Login</a></li>
                        <li><a href="{{ route('register') }}" class="{{ request()->routeIs('register') ? 'active' : '' }}">Register</a></li>
                    @endauth
                </ul>
            </div>
        </div>
        
        <!-- Desktop Navigation -->
        <nav class="navbar">
            <div class="container">
                <a href="{{ route('home') }}" class="navbar-brand desktop-brand">
                    <i class="fas fa-trophy me-2"></i>Sports Club
                </a>
                
                <ul class="navbar-nav">
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                    <li><a href="{{ route('events.index') }}" class="{{ request()->routeIs('events.index') || request()->routeIs('events.show') ? 'active' : '' }}">Events</a></li>
                    
                    @auth
                        <li><a href="{{ route('dashboard.index') }}" class="{{ request()->routeIs('dashboard.index') ? 'active' : '' }}">Dashboard</a></li>
                        <li><a href="{{ route('equipment.index') }}" class="{{ request()->routeIs('equipment.index') || request()->routeIs('equipment.show') ? 'active' : '' }}">Equipment</a></li>
                        <li><a href="{{ route('messages.index') }}" class="{{ request()->routeIs('messages.index') || request()->routeIs('messages.show') || request()->routeIs('messages.conversation') ? 'active' : '' }}">Messages</a></li>
                        <li><a href="{{ route('profile.show') }}" class="{{ request()->routeIs('profile.show') ? 'active' : '' }}">Profile</a></li>
                        @if(auth()->user()->is_admin)
                            <li><a href="/admin" target="_blank">Admin Panel</a></li>
                        @endif
                        
                        <li class="nav-item-desktop">
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-secondary btn-sm">
                                    Logout
                                </button>
                            </form>
                        </li>
                        <li><a href="#" class="notification-bell">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge">3</span>
                        </a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'active' : '' }}">Login</a></li>
                        <li><a href="{{ route('register') }}" class="{{ request()->routeIs('register') ? 'active' : '' }}">Register</a></li>
                    @endauth
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="py-4">
            <div class="container">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 7000)">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
        
        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <p>&copy; {{ date('Y') }} Sports Club Management. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <!-- Scripts -->
    @stack('scripts')
</body>
</html>