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
                <h6>User</h6>
                <h3 class="text-warning">{{ $totalUsers }}</h3>
            </div>
        </div>
    </div>
        <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6>Total Rooms</h6>
                <h3 class="text-danger">{{ $totalApartments }}</h3>
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
</div>

{{-- Recently Created Rooms --}}
<div class="row g-4 mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <h6 class="mb-4">Rooms Status</h6>
                @php
                    $groupedByFloor = $recentApartments->groupBy('floor_id');
                    // Sort floors in custom order (Ground, 1, 2, etc.)
                    $floorOrder = ['Ground Floor', 'First Floor', 'Second Floor', 'Third Floor', 'Fourth Floor', 'Fifth Floor'];
                    $sortedFloors = collect();
                    foreach ($floorOrder as $floorName) {
                        $floorApts = $groupedByFloor->filter(function($apts) use ($floorName) {
                            return $apts->first()->floor->name === $floorName;
                        })->first();
                        if ($floorApts) {
                            $sortedFloors->put($floorApts->first()->floor_id, $floorApts);
                        }
                    }
                    // Add any remaining floors
                    foreach ($groupedByFloor as $floorId => $apts) {
                        if (!$sortedFloors->has($floorId)) {
                            $sortedFloors->put($floorId, $apts);
                        }
                    }
                @endphp
                
                @forelse($sortedFloors as $apartments)
                    @php 
                        $floor = $apartments->first()->floor;
                        // Sort rooms by room_number numerically
                        $sortedRooms = $apartments->sortBy(function($room) {
                            return (int)$room->room_number;
                        });
                    @endphp
                    <div style="margin-bottom: 24px;">
                        <h6 style="color: #374151; font-weight: 600; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 2px solid #e5e7eb;">
                            {{ $floor->name }}
                        </h6>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 15px;">
                            @foreach($sortedRooms as $room)
                                <div style="
                                    background-color: {{ $room->status == 'available' ? '#10b981' : '#f97316' }};
                                    border-radius: 8px;
                                    padding: 16px;
                                    text-align: center;
                                    color: white;
                                    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
                                    transition: transform 0.2s, box-shadow 0.2s;
                                    cursor: pointer;
                                " onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" 
                                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
                                    <div style="font-size: 18px; font-weight: bold; margin-bottom: 4px;">
                                        {{ $room->room_number }}
                                    </div>
                                    <div style="font-size: 11px; opacity: 0.85;">
                                        Apt #{{ $room->apartment_number }}
                                    </div>
                                    <div style="font-size: 10px; opacity: 0.75; margin-top: 8px; border-top: 1px solid rgba(255,255,255,0.3); padding-top: 8px;">
                                        ${{ number_format($room->monthly_rent, 0) }}/mo
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="alert alert-info" role="alert">
                        No rooms created yet
                    </div>
                @endforelse
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
