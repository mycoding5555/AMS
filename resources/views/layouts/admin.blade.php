<!DOCTYPE html>
@php
    $theme = \App\Models\Setting::get('theme', 'light');
    $language = \App\Models\Setting::get('language', 'en');
    $appName = \App\Models\Setting::get('app_name', 'AMS');
@endphp
<html lang="{{ $language }}" data-bs-theme="{{ $theme }}">
<head>
    <meta charset="UTF-8">
    <title>{{ __('app.dashboard') }} | {{ $appName }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Khmer Font --}}
    @if($language === 'km')
    <link href="https://fonts.googleapis.com/css2?family=Battambang:wght@400;700&display=swap" rel="stylesheet">
    @endif

    <style>
        /* Khmer Font */
        @if($language === 'km')
        body, .nav-link, h1, h2, h3, h4, h5, h6, p, span, label, button, input, select, textarea {
            font-family: 'Battambang', cursive, sans-serif !important;
        }
        @endif
        
        /* Dark Theme Overrides */
        [data-bs-theme="dark"] {
            --bs-body-bg: #1a1d21;
            --bs-body-color: #e9ecef;
        }
        
        [data-bs-theme="dark"] .bg-light {
            background-color: #1a1d21 !important;
        }
        
        [data-bs-theme="dark"] .bg-white {
            background-color: #212529 !important;
        }
        
        [data-bs-theme="dark"] .card {
            background-color: #212529;
            border-color: #343a40;
        }
        
        [data-bs-theme="dark"] .table {
            --bs-table-bg: #212529;
            --bs-table-striped-bg: #2c3034;
            --bs-table-hover-bg: #323539;
            color: #e9ecef;
        }
        
        [data-bs-theme="dark"] .form-control,
        [data-bs-theme="dark"] .form-select {
            background-color: #2b3035;
            border-color: #495057;
            color: #e9ecef;
        }
        
        [data-bs-theme="dark"] .navbar-light {
            background-color: #212529 !important;
        }
        
        [data-bs-theme="dark"] .navbar-text,
        [data-bs-theme="dark"] .text-muted {
            color: #adb5bd !important;
        }
        
        [data-bs-theme="dark"] .modal-content {
            background-color: #212529;
            border-color: #495057;
        }
        
        [data-bs-theme="dark"] .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        /* Sidebar styles */
        .sidebar {
            width: 250px;
            min-height: 100vh;
            transition: all 0.3s ease;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
        }
        
        .sidebar.collapsed {
            width: 0;
            padding: 0 !important;
            overflow: hidden;
        }
        
        .sidebar.collapsed * {
            opacity: 0;
            visibility: hidden;
        }
        
        .main-content {
            margin-left: 250px;
            transition: all 0.3s ease;
            width: calc(100% - 250px);
        }
        
        .main-content.expanded {
            margin-left: 0;
            width: 100%;
        }
        
        .hamburger-btn {
            border: none;
            background: transparent;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
        }
        
        .hamburger-btn:hover {
            background-color: rgba(0,0,0,0.1);
            border-radius: 4px;
        }
        
        /* Mobile responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 250px;
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .sidebar.collapsed {
                transform: translateX(-100%);
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 999;
            }
            
            .overlay.show {
                display: block;
            }
        }
    </style>
</head>
<body class="{{ $theme === 'dark' ? 'bg-dark' : 'bg-light' }}">

{{-- Overlay for mobile --}}
<div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

<div class="d-flex">
    {{-- Sidebar --}}
    <nav class="sidebar bg-dark text-white p-3" id="sidebar">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">{{ $appName }}</h5>
            <button class="hamburger-btn text-white d-md-none" onclick="toggleSidebar()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <ul class="nav flex-column gap-2">
            <li><a href="{{ route('admin.dashboard') }}" class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'bg-primary rounded' : '' }}"><i class="bi bi-speedometer2 me-2"></i>{{ __('app.dashboard') }}</a></li>
            <li><a href="{{ route('admin.users.index') }}" class="nav-link text-white {{ request()->routeIs('admin.users.*') ? 'bg-primary rounded' : '' }}"><i class="bi bi-people me-2"></i>{{ __('app.users') }}</a></li>
            <li><a href="{{ route('admin.rooms.index') }}" class="nav-link text-white {{ request()->routeIs('admin.rooms.*') ? 'bg-primary rounded' : '' }}"><i class="bi bi-door-open me-2"></i>{{ __('app.rooms') }}</a></li>
            <li><a href="{{ route('admin.tenants.index') }}" class="nav-link text-white {{ request()->routeIs('admin.tenants.index') || request()->routeIs('admin.tenants.create') || request()->routeIs('admin.tenants.edit') || request()->routeIs('admin.tenants.show') ? 'bg-primary rounded' : '' }}"><i class="bi bi-person-badge me-2"></i>{{ __('app.tenants') }}</a></li>
            <li><a href="{{ route('admin.tenants.archived.index') }}" class="nav-link text-white {{ request()->routeIs('admin.tenants.archived.*') ? 'bg-primary rounded' : '' }}"><i class="bi bi-archive me-2"></i>{{ __('app.archived_tenants') }}</a></li>
            <li><a href="{{ route('admin.apartments.index') }}" class="nav-link text-white {{ request()->routeIs('admin.apartments.*') ? 'bg-primary rounded' : '' }}"><i class="bi bi-building me-2"></i>{{ __('app.apartments') }}</a></li>
            <li><a href="{{ route('admin.expenses.index') }}" class="nav-link text-white {{ request()->routeIs('admin.expenses.*') ? 'bg-primary rounded' : '' }}"><i class="bi bi-cash-stack me-2"></i>{{ __('app.expenses') }}</a></li>
            <li><a href="{{ route('admin.settings.index') }}" class="nav-link text-white {{ request()->routeIs('admin.settings.*') ? 'bg-primary rounded' : '' }}"><i class="bi bi-gear me-2"></i>{{ __('app.settings') }}</a></li>
        </ul>
    </nav>

    {{-- Main Content --}}
    <div class="main-content flex-grow-1" id="mainContent">
        {{-- Topbar --}}
        <nav class="navbar {{ $theme === 'dark' ? 'navbar-dark bg-dark' : 'navbar-light bg-white' }} shadow-sm px-4">
            <div class="d-flex align-items-center">
                <button class="hamburger-btn me-3 {{ $theme === 'dark' ? 'text-white' : '' }}" onclick="toggleSidebar()" title="Toggle Sidebar">
                    <i class="bi bi-list" id="hamburgerIcon"></i>
                </button>
                <span class="navbar-text">
                    {{ __('app.welcome') }}, {{ auth()->user()->name }}
                </span>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-danger btn-sm">{{ __('app.logout') }}</button>
            </form>
        </nav>

        <main class="p-4">
            @yield('content')
        </main>
    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const overlay = document.getElementById('overlay');
        const hamburgerIcon = document.getElementById('hamburgerIcon');
        
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');
        
        // For mobile
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
        
        // Toggle hamburger icon
        if (sidebar.classList.contains('collapsed')) {
            hamburgerIcon.classList.remove('bi-x-lg');
            hamburgerIcon.classList.add('bi-list');
        } else {
            hamburgerIcon.classList.remove('bi-list');
            hamburgerIcon.classList.add('bi-x-lg');
        }
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const hamburgerBtn = event.target.closest('.hamburger-btn');
        
        if (window.innerWidth <= 768 && 
            !sidebar.contains(event.target) && 
            !hamburgerBtn && 
            sidebar.classList.contains('show')) {
            toggleSidebar();
        }
    });
</script>

</body>
</html>
