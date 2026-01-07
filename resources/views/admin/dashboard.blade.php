@extends('layouts.admin')

@section('content')

<h4 class="mb-4">Dashboard Overview</h4>

{{-- KPI Cards --}}
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Total Revenue</h6>
                <h3 class="text-success">${{ number_format($totalRevenue, 2) }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Overdue Payments</h6>
                <h3 class="text-danger">{{ $overduePayments }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Occupancy Rate</h6>
                <h3 class="text-primary">{{ $occupancyRate }}%</h3>
            </div>
        </div>
    </div>
</div>

{{-- Charts --}}
<div class="row g-4">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Monthly Revenue</h6>
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Payment Status</h6>
                <canvas id="paymentChart"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Chart Scripts --}}
<script>
const revenueCtx = document.getElementById('revenueChart');
new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: @json($months),
        datasets: [{
            label: 'Revenue',
            data: @json($monthlyRevenue)
        }]
    }
});

const paymentCtx = document.getElementById('paymentChart');
new Chart(paymentCtx, {
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
