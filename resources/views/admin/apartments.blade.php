@extends('layouts.admin')

@section('content')
<h4>Room Management</h4>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<form method="POST" action="{{ route('admin.apartments.store') }}" class="row g-2 mb-3">
    @csrf
    <div class="col-md-3">
        <select name="floor_id" class="form-select" required>
            <option value="">Select Floor</option>
            @foreach($floors as $floor)
                <option value="{{ $floor->id }}">{{ $floor->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <input name="room_number" class="form-control" placeholder="Room Number" required>
    </div>
    <div class="col-md-2">
        <input name="apartment_number" class="form-control" placeholder="Apt No" required>
    </div>
    <div class="col-md-2">
        <input name="monthly_rent" class="form-control" placeholder="Rent" required>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary">Add Room</button>
    </div>
</form>

<table class="table table-bordered">
<tr>
    <th>Floor</th>
    <th>Room Number</th>
    <th>Apartment No</th>
    <th>Rent</th>
    <th>Status</th>
    <th>Action</th>
</tr>

@foreach($apartments as $room)
<tr>
<form method="POST" action="{{ route('admin.apartments.update',$room) }}">
@csrf @method('PUT')
<td>{{ $room->floor->name }}</td>
<td><input name="room_number" value="{{ $room->room_number }}" class="form-control" required></td>
<td><input name="apartment_number" value="{{ $room->apartment_number }}" class="form-control" required></td>
<td><input name="monthly_rent" value="{{ $room->monthly_rent }}" class="form-control" required></td>
<td>
    <select name="status" class="form-select">
        <option value="available" {{ $room->status=='available'?'selected':'' }}>Available</option>
        <option value="occupied" {{ $room->status=='occupied'?'selected':'' }}>Occupied</option>
    </select>
</td>
<td><button class="btn btn-sm btn-success">Save</button></td>
</form>
</tr>
@endforeach
</table>
@endsection
