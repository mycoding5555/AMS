<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Supervisor | AMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
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
<body class="bg-light">

{{-- Overlay for mobile --}}
<div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

<div class="d-flex">
    {{-- Sidebar --}}
    <nav class="sidebar bg-primary text-white p-3" id="sidebar">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0">👨‍💼 Supervisor</h5>
            <button class="hamburger-btn text-white d-md-none" onclick="toggleSidebar()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <ul class="nav flex-column gap-2">
            <li><a href="{{ route('supervisor.dashboard') }}" class="nav-link text-white"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
            <li><a href="{{ route('supervisor.customers.index') }}" class="nav-link text-white"><i class="bi bi-people me-2"></i>Customers</a></li>
            <li><a href="{{ route('supervisor.rentals.index') }}" class="nav-link text-white"><i class="bi bi-house-door me-2"></i>Rentals</a></li>
            <li><a href="{{ route('supervisor.payments.index') }}" class="nav-link text-white"><i class="bi bi-credit-card me-2"></i>Payments</a></li>
        </ul>
    </nav>

    {{-- Content --}}
    <div class="main-content flex-grow-1" id="mainContent">
        <nav class="navbar bg-white shadow-sm px-4">
            <div class="d-flex align-items-center">
                <button class="hamburger-btn me-3" onclick="toggleSidebar()" title="Toggle Sidebar">
                    <i class="bi bi-list" id="hamburgerIcon"></i>
                </button>
                <span>Welcome, {{ auth()->user()->name }}</span>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-danger btn-sm">Logout</button>
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
