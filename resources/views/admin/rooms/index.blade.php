@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Room Management</h4>
    <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle"></i> Add New Room
    </a>
</div>

{{-- Recently Created Rooms --}}
<div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 32px; margin-bottom: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.12);">
    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
        <div style="width: 40px; height: 40px; background: #f5f5f7; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #0071e3;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7"></rect>
                <rect x="14" y="3" width="7" height="7"></rect>
                <rect x="14" y="14" width="7" height="7"></rect>
                <rect x="3" y="14" width="7" height="7"></rect>
            </svg>
        </div>
        <h3 style="font-size: 18px; font-weight: 600; color: #1d1d1f; margin: 0;">Rooms Status</h3>
    </div>

    @php
        $groupedByFloor = $recentApartments->groupBy('floor_id');
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
        foreach ($groupedByFloor as $floorId => $apts) {
            if (!$sortedFloors->has($floorId)) {
                $sortedFloors->put($floorId, $apts);
            }
        }
    @endphp
    
    @forelse($sortedFloors as $apartments)
        @php 
            $floor = $apartments->first()->floor;
            $sortedRooms = $apartments->sortBy(function($room) {
                return (int)$room->room_number;
            });
        @endphp
        <div style="margin-bottom: 32px;">
            <h5 style="color: #1d1d1f; font-weight: 600; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 2px solid #d5d5d7;">
                {{ $floor->name }}
            </h5>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 16px;">
                @foreach($sortedRooms as $room)
                    @php
                        $tenant = $room->tenants()->where('status', 'active')->first();
                        $isOccupied = $tenant ? true : false;
                    @endphp
                    <a href="{{ $isOccupied ? route('admin.tenants.index') . '?room_id=' . $room->id : route('admin.tenants.create') . '?room_id=' . $room->id }}" style="text-decoration: none;">
                        <div style="
                            background-color: {{ $isOccupied ? '#FF9500' : '#34C759' }};
                            border-radius: 12px;
                            padding: 20px 16px;
                            text-align: center;
                            color: white;
                            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
                            transition: all 0.3s ease;
                            cursor: pointer;
                            height: 100%;
                            display: flex;
                            flex-direction: column;
                            justify-content: space-between;
                        " 
                        onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='0 8px 16px rgba(0,0,0,0.2)'" 
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.12)'">
                            <div>
                                <div style="font-size: 20px; font-weight: 700; margin-bottom: 6px;">
                                    {{ $room->room_number }}
                                </div>
                                <div style="font-size: 12px; opacity: 0.85;">
                                    Apt #{{ $room->apartment_number }}
                                </div>
                            </div>
                            <div>
                                @if($tenant)
                                    @if($tenant->photo_path)
                                        <div style="margin-top: 12px; text-align: center;">
                                            <img src="{{ asset('storage/' . $tenant->photo_path) }}" alt="{{ $tenant->name }}" 
                                                 style="width: 56px; height: 56px; border-radius: 50%; border: 3px solid white; object-fit: cover;">
                                        </div>
                                    @endif
                                    <div style="font-size: 10px; opacity: 0.95; margin-top: 10px; border-top: 1px solid rgba(255,255,255,0.4); padding-top: 8px; text-align: center;">
                                        <strong>{{ \Illuminate\Support\Str::limit($tenant->name, 14, '...') }}</strong>
                                    </div>
                                @endif
                                <div style="font-size: 11px; opacity: 0.8; margin-top: 8px;">
                                    ${{ number_format($room->monthly_rent, 0) }}/mo
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @empty
        <div style="background: #f5f5f7; border: 1px solid #d5d5d7; border-radius: 12px; padding: 20px; text-align: center; color: #86868b;">
            <p style="margin: 0;">No rooms created yet</p>
        </div>
    @endforelse
</div>



<style>
    .table-hover tbody tr:hover {
        background-color: #f5f5f5;
    }
</style>

@endsection
