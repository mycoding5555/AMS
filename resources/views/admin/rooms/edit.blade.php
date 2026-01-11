@extends('layouts.admin')

@section('content')

<div class="mb-4">
    <h4>Edit Room</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.rooms.index') }}">Rooms</a></li>
            <li class="breadcrumb-item active">Edit Room</li>
        </ol>
    </nav>
</div>

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Validation Error!</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.rooms.update', $room) }}" class="row g-3">
            @csrf
            @method('PUT')

            <div class="col-md-6">
                <label for="floor_id" class="form-label">Floor <span class="text-danger">*</span></label>
                <select name="floor_id" id="floor_id" class="form-select @error('floor_id') is-invalid @enderror" required>
                    <option value="">-- Select Floor --</option>
                    @foreach($floors as $floor)
                        <option value="{{ $floor->id }}" {{ $room->floor_id == $floor->id ? 'selected' : '' }}>
                            {{ $floor->name }}
                        </option>
                    @endforeach
                </select>
                @error('floor_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="room_number" class="form-label">Room Number <span class="text-danger">*</span></label>
                <input type="text" name="room_number" id="room_number" class="form-control @error('room_number') is-invalid @enderror" 
                       placeholder="e.g., 101" value="{{ old('room_number', $room->room_number) }}" required>
                @error('room_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="apartment_number" class="form-label">Apartment Number <span class="text-danger">*</span></label>
                <input type="text" name="apartment_number" id="apartment_number" class="form-control @error('apartment_number') is-invalid @enderror" 
                       placeholder="e.g., APT-101" value="{{ old('apartment_number', $room->apartment_number) }}" required>
                @error('apartment_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="monthly_rent" class="form-label">Monthly Rent <span class="text-danger">*</span></label>
                <input type="number" name="monthly_rent" id="monthly_rent" class="form-control @error('monthly_rent') is-invalid @enderror" 
                       placeholder="0.00" step="0.01" value="{{ old('monthly_rent', $room->monthly_rent) }}" required>
                @error('monthly_rent')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="">-- Select Status --</option>
                    <option value="available" {{ $room->status == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="occupied" {{ $room->status == 'occupied' ? 'selected' : '' }}>Occupied</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="supervisor_id" class="form-label">Assign Supervisor (Optional)</label>
                <select name="supervisor_id" id="supervisor_id" class="form-select @error('supervisor_id') is-invalid @enderror">
                    <option value="">-- Select Supervisor --</option>
                    @foreach($supervisors as $supervisor)
                        <option value="{{ $supervisor->id }}" {{ $room->supervisor_id == $supervisor->id ? 'selected' : '' }}>
                            {{ $supervisor->name }}
                        </option>
                    @endforeach
                </select>
                @error('supervisor_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Update Room
                    </button>
                    <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Cancel
                    </a>
                    <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this room?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
