<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sports Club')</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/notifications.css') }}">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body>
    <div id="app" x-data="{
        mobileMenuOpen: false,
        notifications: [],
        notificationCount: 0,
        showNotifications: false,
        init() {
            this.loadNotificationCount();
        },
        loadNotificationCount() {
            @auth
            fetch('/notifications/count')
                .then(response => response.json())
                .then(data => {
                    this.notificationCount = data.count;
                });
            @endauth
        },
        loadNotifications() {
            @auth
            fetch('/notifications')
                .then(response => response.json())
                .then(data => {
                    this.notifications = data.notifications;
                    this.notificationCount = data.count;
                    this.showNotifications = true;
                });
            @endauth
        },
        markAsRead(notification) {
            // Handle notification click based on type
            if (notification.type === 'event') {
                // Redirect to event page
                window.location.href = `/events/${notification.event_id}`;
            } else {
                // For equipment notifications, just hide the dropdown
                this.showNotifications = false;
            }
            
            // Decrease notification count
            this.notificationCount = Math.max(0, this.notificationCount - 1);
            
            // Remove the notification from the list
            this.notifications = this.notifications.filter(n => n.id !== notification.id);
            
            // If this was the last notification, hide the dropdown after a short delay
            if (this.notificationCount === 0) {
                setTimeout(() => {
                    this.showNotifications = false;
                }, 100);
            }
        },
        toggleNotifications() {
            if (this.showNotifications) {
                this.showNotifications = false;
                // Refresh notification count when closing
                this.loadNotificationCount();
            } else {
                this.loadNotifications();
            }
        }
    }">
        <!-- Mobile Header and Navigation Container -->
        <div class="mobile-container">
            
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
                    @auth
                    <div class="dropdown notification-dropdown">
                        <a href="#" class="notification-bell" @click.prevent="toggleNotifications()">
                            <i class="fas fa-bell"></i>
                            <span class="notification-badge" x-show="notificationCount > 0" x-text="notificationCount"></span>
                        </a>
                        <div class="dropdown-menu notification-menu" x-show="showNotifications">
                            <template x-if="notifications.length > 0">
                                <template x-for="notification in notifications" :key="notification.id">
                                    <div class="notification-item" @click="markAsRead(notification)">
                                        <div class="notification-content">
                                            <p x-text="notification.message"></p>
                                            <small x-text="notification.time_remaining"></small>
                                        </div>
                                    </div>
                                </template>
                            </template>
                            <template x-if="notifications.length === 0">
                                <div class="notification-item">
                                    <div class="notification-content no-notifications">
                                        <p>You have no notifications at the moment</p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    @endauth
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
                        <li class="nav-item-desktop">
                            <div class="dropdown notification-dropdown">
                                <a href="#" class="notification-bell" @click.prevent="toggleNotifications()">
                                    <i class="fas fa-bell"></i>
                                    <span class="notification-badge" x-show="notificationCount > 0" x-text="notificationCount"></span>
                                </a>
                                <div class="dropdown-menu notification-menu" x-show="showNotifications">
                                    <template x-if="notifications.length > 0">
                                        <template x-for="notification in notifications" :key="notification.id">
                                            <div class="notification-item" @click="markAsRead(notification)">
                                                <div class="notification-content">
                                                    <p x-text="notification.message"></p>
                                                    <small x-text="notification.time_remaining"></small>
                                                </div>
                                            </div>
                                        </template>
                                    </template>
                                    <template x-if="notifications.length === 0">
                                        <div class="notification-item">
                                            <div class="notification-content no-notifications">
                                                <p>You have no notifications at the moment</p>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </li>
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