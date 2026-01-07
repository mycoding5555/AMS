@extends('layouts.supervisor')

@section('content')

<h4 class="mb-4">Supervisor Overview</h4>

{{-- KPI Cards --}}
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Active Tenants</h6>
                <h3>{{ $activeTenants }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Active Rentals</h6>
                <h3>{{ $activeRentals }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Pending Payments</h6>
                <h3 class="text-warning">{{ $pendingPayments }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Overdue Payments</h6>
                <h3 class="text-danger">{{ $overduePayments }}</h3>
            </div>
        </div>
    </div>
</div>

{{-- Charts --}}
<div class="row g-4">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Monthly Collected Rent</h6>
                <canvas id="rentChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Payment Status</h6>
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
new Chart(document.getElementById('rentChart'), {
    type: 'bar',
    data: {
        labels: @json($months),
        datasets: [{
            label: 'Collected Rent',
            data: @json($monthlyRent)
        }]
    }
});

new Chart(document.getElementById('statusChart'), {
    type: 'pie',
    data: {
        labels: ['Paid', 'Pending', 'Overdue'],
        datasets: [{
            data: [
                {{ $paymentStats['paid'] }},
                {{ $paymentStats['pending'] }},
                {{ $paymentStats['overdue'] }}
            ]
        }]
    }
});
</script>

@endsection
