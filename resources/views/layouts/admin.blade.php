<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | AMS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">

<div class="d-flex">
    {{-- Sidebar --}}
    <nav class="bg-dark text-white p-3" style="width:250px; min-height:100vh;">
        <h5 class="mb-4">AMS Admin</h5>

        <ul class="nav flex-column gap-2">
            <li><a href="{{ route('admin.dashboard') }}" class="nav-link text-white">Dashboard</a></li>
            <li><a href="{{ route('admin.users.index') }}" class="nav-link text-white">Users</a></li>
            <li><a href="{{ route('admin.rooms.index') }}" class="nav-link text-white">Room</a></li>
            <li><a href="{{ route('admin.tenants.index') }}" class="nav-link text-white">Tenant</a></li>
            <li><a href="{{ route('admin.apartments.index') }}" class="nav-link text-white">Apartments</a></li>
            <li><a href="{{ route('admin.expenses.index') }}" class="nav-link text-white">Expenses</a></li>
        </ul>
    </nav>

    {{-- Main Content --}}
    <div class="flex-grow-1">
        {{-- Topbar --}}
        <nav class="navbar navbar-light bg-white shadow-sm px-4">
            <span class="navbar-text">
                Welcome, {{ auth()->user()->name }}
            </span>

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

</body>
</html>
