@extends('layouts.admin')

@section('content')

<h4 class="mb-4">Dashboard Overview</h4>

{{-- KPI Cards --}}
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Total Revenue</h6>
                <h3 class="text-success">${{ number_format($totalRevenue, 2) }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Total Rooms</h6>
                <h3 class="text-info">{{ $totalApartments }}</h3>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Occupied Rooms</h6>
                <h3 class="text-primary">{{ $occupiedApartments }}</h3>
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

{{-- Recently Created Rooms --}}
<div class="row g-4 mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="mb-3">Recently Created Rooms</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Floor</th>
                                <th>Room Number</th>
                                <th>Apartment No</th>
                                <th>Monthly Rent</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentApartments as $room)
                            <tr>
                                <td>{{ $room->floor->name }}</td>
                                <td>{{ $room->room_number }}</td>
                                <td>{{ $room->apartment_number }}</td>
                                <td>${{ number_format($room->monthly_rent, 2) }}</td>
                                <td>
                                    <span class="badge {{ $room->status == 'available' ? 'bg-success' : 'bg-warning' }}">
                                        {{ ucfirst($room->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No rooms created yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
