@extends('layouts.admin')

@section('content')

<div style="background: linear-gradient(135deg, #f5f5f7 0%, #ffffff 100%); border-radius: 20px; padding: 32px; margin-bottom: 32px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 32px; font-weight: 600; color: #1d1d1f; margin: 0;">Room Management</h1>
            <p style="color: #86868b; margin: 8px 0 0 0;">Add, edit, and manage all rooms in your property</p>
        </div>
        <div style="width: 56px; height: 56px; background: #f5f5f7; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #0071e3;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7"></rect>
                <rect x="14" y="3" width="7" height="7"></rect>
                <rect x="14" y="14" width="7" height="7"></rect>
                <rect x="3" y="14" width="7" height="7"></rect>
            </svg>
        </div>
    </div>
</div>

@if ($errors->any())
    <div style="background: #fef2f2; border: 1px solid #f87171; border-radius: 12px; padding: 16px; margin-bottom: 24px; color: #991b1b;">
        <div style="display: flex; align-items: start; gap: 12px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0; margin-top: 2px;">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li style="margin-bottom: 4px;">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

@if (session('success'))
    <div style="background: #f0fdf4; border: 1px solid #22c55e; border-radius: 12px; padding: 16px; margin-bottom: 24px; color: #15803d;">
        <div style="display: flex; align-items: start; gap: 12px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0; margin-top: 2px;">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <p style="font-weight: 500; margin: 0;">{{ session('success') }}</p>
        </div>
    </div>
@endif

<div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 24px; margin-bottom: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.12);">
    <h3 style="font-weight: 600; color: #1d1d1f; margin: 0 0 20px 0; font-size: 18px;">Add New Room</h3>
    <form method="POST" action="{{ route('admin.apartments.store') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
        @csrf
        <div>
            <label style="display: block; color: #86868b; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Floor</label>
            <select name="floor_id" style="width: 100%; padding: 12px; border: 1px solid #d5d5d7; border-radius: 8px; background: white; color: #1d1d1f; font-size: 14px; cursor: pointer; transition: border-color 0.2s;" required onmouseover="this.style.borderColor='#0071e3'" onmouseout="this.style.borderColor='#d5d5d7'">
                <option value="">Select Floor</option>
                @foreach($floors as $floor)
                    <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="display: block; color: #86868b; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Room Number</label>
            <input name="room_number" style="width: 100%; padding: 12px; border: 1px solid #d5d5d7; border-radius: 8px; background: white; color: #1d1d1f; font-size: 14px; transition: border-color 0.2s;" placeholder="e.g., 101" required onmouseover="this.style.borderColor='#0071e3'" onmouseout="this.style.borderColor='#d5d5d7'">
        </div>
        <div>
            <label style="display: block; color: #86868b; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Apartment No</label>
            <input name="apartment_number" style="width: 100%; padding: 12px; border: 1px solid #d5d5d7; border-radius: 8px; background: white; color: #1d1d1f; font-size: 14px; transition: border-color 0.2s;" placeholder="e.g., A101" required onmouseover="this.style.borderColor='#0071e3'" onmouseout="this.style.borderColor='#d5d5d7'">
        </div>
        <div>
            <label style="display: block; color: #86868b; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Monthly Rent</label>
            <input name="monthly_rent" style="width: 100%; padding: 12px; border: 1px solid #d5d5d7; border-radius: 8px; background: white; color: #1d1d1f; font-size: 14px; transition: border-color 0.2s;" placeholder="$0.00" required type="number" step="0.01" onmouseover="this.style.borderColor='#0071e3'" onmouseout="this.style.borderColor='#d5d5d7'">
        </div>
        <div>
            <label style="display: block; color: #86868b; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Supervisor</label>
            <select name="supervisor_id" style="width: 100%; padding: 12px; border: 1px solid #d5d5d7; border-radius: 8px; background: white; color: #1d1d1f; font-size: 14px; cursor: pointer; transition: border-color 0.2s;" onmouseover="this.style.borderColor='#0071e3'" onmouseout="this.style.borderColor='#d5d5d7'">
                <option value="">No Supervisor</option>
                @foreach($supervisors as $supervisor)
                    <option value="{{ $supervisor->id }}">{{ $supervisor->name }}</option>
                @endforeach
            </select>
        </div>
        <div style="display: flex; align-items: flex-end;">
            <button type="submit" style="width: 100%; padding: 12px 24px; background: #0071e3; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 14px; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#0077ed'" onmouseout="this.style.backgroundColor='#0071e3'">
                Add Room
            </button>
        </div>
    </form>
</div>

@php
    $groupedByFloor = $apartments->groupBy('floor_id');
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
        $floorId = 'floor-' . $floor->id;
    @endphp
    <div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.12); overflow: hidden;">
        <div onclick="toggleFloor('{{ $floorId }}')" style="padding: 20px 24px; background: #f5f5f7; border-bottom: 1px solid #d5d5d7; cursor: pointer; display: flex; justify-content: space-between; align-items: center; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#ececf0'" onmouseout="this.style.backgroundColor='#f5f5f7'">
            <div>
                <h4 style="margin: 0; color: #1d1d1f; font-weight: 600; font-size: 16px; display: flex; align-items: center; gap: 12px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" id="chevron-{{ $floorId }}" style="transition: transform 0.2s;">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                    {{ $floor->name }}
                    <span style="background: #0071e3; color: white; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;">{{ $sortedRooms->count() }} Room(s)</span>
                </h4>
            </div>
            <div style="color: #86868b; font-size: 14px;">
                <span style="margin-right: 20px;">
                    <span style="color: #34c759; font-weight: 600;">{{ $sortedRooms->where('status', 'available')->count() }}</span> Available
                </span>
                <span>
                    <span style="color: #ff9500; font-weight: 600;">{{ $sortedRooms->where('status', 'occupied')->count() }}</span> Occupied
                </span>
            </div>
        </div>
        
        <div id="{{ $floorId }}" style="display: none;">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <thead>
                        <tr style="background-color: #f5f5f7; border-bottom: 1px solid #d5d5d7;">
                            <th style="padding: 16px 24px; text-align: left; font-weight: 600; color: #86868b; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Room #</th>
                            <th style="padding: 16px 24px; text-align: left; font-weight: 600; color: #86868b; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Apt No</th>
                            <th style="padding: 16px 24px; text-align: left; font-weight: 600; color: #86868b; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Monthly Rent</th>
                            <th style="padding: 16px 24px; text-align: left; font-weight: 600; color: #86868b; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Supervisor</th>
                            <th style="padding: 16px 24px; text-align: left; font-weight: 600; color: #86868b; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Status</th>
                            <th style="padding: 16px 24px; text-align: center; font-weight: 600; color: #86868b; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sortedRooms as $room)
                        <tr style="border-bottom: 1px solid #d5d5d7; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f5f5f7'" onmouseout="this.style.backgroundColor='white'">
                            <form method="POST" action="{{ route('admin.apartments.update', $room) }}" style="display: contents;">
                                @csrf @method('PUT')
                                <td style="padding: 16px 24px; color: #1d1d1f; font-weight: 600;">{{ $room->room_number }}</td>
                                <td style="padding: 16px 24px;">
                                    <input name="apartment_number" value="{{ $room->apartment_number }}" style="padding: 8px 12px; border: 1px solid #d5d5d7; border-radius: 6px; background: white; color: #1d1d1f; font-size: 14px; width: 100%; max-width: 120px;" required>
                                </td>
                                <td style="padding: 16px 24px;">
                                    <input name="monthly_rent" value="{{ $room->monthly_rent }}" style="padding: 8px 12px; border: 1px solid #d5d5d7; border-radius: 6px; background: white; color: #1d1d1f; font-size: 14px; width: 100%; max-width: 120px;" required type="number" step="0.01">
                                </td>
                                <td style="padding: 16px 24px;">
                                    <select name="supervisor_id" style="padding: 8px 12px; border: 1px solid #d5d5d7; border-radius: 6px; background: white; color: #1d1d1f; font-size: 14px; cursor: pointer; width: 100%; max-width: 150px;">
                                        <option value="">None</option>
                                        @foreach($supervisors as $supervisor)
                                            <option value="{{ $supervisor->id }}" {{ $room->supervisor_id == $supervisor->id ? 'selected' : '' }}>{{ $supervisor->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="padding: 16px 24px;">
                                    <select name="status" style="padding: 8px 12px; border: 1px solid #d5d5d7; border-radius: 6px; background: white; color: #1d1d1f; font-size: 14px; cursor: pointer;">
                                        <option value="available" {{ $room->status=='available'?'selected':'' }} style="color: #34c759;">Available</option>
                                        <option value="occupied" {{ $room->status=='occupied'?'selected':'' }} style="color: #ff9500;">Occupied</option>
                                    </select>
                                </td>
                                <td style="padding: 16px 24px; text-align: center;">
                                    <div style="display: flex; gap: 8px; justify-content: center;">
                                        <button type="submit" style="padding: 8px 12px; background: #34c759; color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; font-size: 12px; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#30b452'" onmouseout="this.style.backgroundColor='#34c759'">
                                            Save
                                        </button>
                                        <button type="button" onclick="deleteRoom({{ $room->id }})" style="padding: 8px 12px; background: #ff3b30; color: white; border: none; border-radius: 6px; font-weight: 500; cursor: pointer; font-size: 12px; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#ff1744'" onmouseout="this.style.backgroundColor='#ff3b30'">
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </form>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@empty
    <div style="background: #f5f5f7; border: 1px solid #d5d5d7; border-radius: 12px; padding: 32px; text-align: center; color: #86868b;">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 16px; opacity: 0.5;">
            <rect x="3" y="3" width="7" height="7"></rect>
            <rect x="14" y="3" width="7" height="7"></rect>
            <rect x="14" y="14" width="7" height="7"></rect>
            <rect x="3" y="14" width="7" height="7"></rect>
        </svg>
        <p style="font-size: 16px; margin: 0 0 8px 0; font-weight: 500;">No rooms created yet</p>
        <p style="margin: 0;">Add your first room using the form above.</p>
    </div>
@endforelse

<script>
    function toggleFloor(floorId) {
        const element = document.getElementById(floorId);
        const chevron = document.getElementById('chevron-' + floorId);
        const isHidden = element.style.display === 'none';
        element.style.display = isHidden ? 'block' : 'none';
        chevron.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
    }

    function deleteRoom(roomId) {
        if (confirm('Are you sure you want to delete this room?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/apartments/${roomId}`;
            form.innerHTML = `
                @csrf
                @method('DELETE')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

@endsection
