@extends('layouts.admin')

@section('content')
<h4>Room Management</h4>

<form method="POST" class="row g-2 mb-3">
    @csrf
    <div class="col-md-3">
        <select name="floor_id" class="form-select">
            @foreach($floors as $floor)
                <option value="{{ $floor->id }}">{{ $floor->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <input name="apartment_number" class="form-control" placeholder="Room No">
    </div>
    <div class="col-md-3">
        <input name="monthly_rent" class="form-control" placeholder="Rent">
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary">Add Room</button>
    </div>
</form>

<table class="table table-bordered">
<tr>
    <th>Floor</th>
    <th>Room</th>
    <th>Rent</th>
    <th>Status</th>
    <th>Action</th>
</tr>

@foreach($apartments as $room)
<tr>
<form method="POST" action="{{ route('admin.apartments.update',$room) }}">
@csrf @method('PUT')
<td>{{ $room->floor->name }}</td>
<td><input name="apartment_number" value="{{ $room->apartment_number }}" class="form-control"></td>
<td><input name="monthly_rent" value="{{ $room->monthly_rent }}" class="form-control"></td>
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
