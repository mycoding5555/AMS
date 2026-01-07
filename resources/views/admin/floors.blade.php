@extends('layouts.admin')

@section('content')
<h4>Floor Management</h4>

<form method="POST" class="row g-2 mb-3">
    @csrf
    <div class="col-md-4">
        <input name="name" class="form-control" placeholder="Floor name (e.g Floor 1)">
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary">Add Floor</button>
    </div>
</form>

<table class="table table-bordered">
    <tr>
        <th>Floor</th>
        <th>Rooms</th>
        <th>Action</th>
    </tr>
    @foreach($floors as $floor)
    <tr>
        <form method="POST" action="{{ route('admin.floors.update',$floor) }}">
            @csrf @method('PUT')
            <td>
                <input name="name" value="{{ $floor->name }}" class="form-control">
            </td>
            <td>{{ $floor->apartments_count }}</td>
            <td>
                <button class="btn btn-sm btn-success">Save</button>
            </td>
        </form>
    </tr>
    @endforeach
</table>
@endsection
