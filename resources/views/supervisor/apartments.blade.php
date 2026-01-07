@extends('layouts.supervisor')

@section('content')
<h4>Available Rooms</h4>

<table class="table table-bordered">
<tr>
    <th>Floor</th>
    <th>Room</th>
    <th>Rent</th>
    <th>Status</th>
</tr>

@foreach($apartments as $room)
<tr>
    <td>{{ $room->floor->name }}</td>
    <td>{{ $room->apartment_number }}</td>
    <td>${{ $room->monthly_rent }}</td>
    <td>
        <span class="badge bg-{{ $room->status=='available'?'success':'secondary' }}">
            {{ ucfirst($room->status) }}
        </span>
    </td>
</tr>
@endforeach
</table>
@endsection
