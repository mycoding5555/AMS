@extends('layouts.admin')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Room Management</h4>
    <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle"></i> Add New Room
    </a>
</div>

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Floor</th>
                        <th>Room Number</th>
                        <th>Apartment Number</th>
                        <th>Monthly Rent</th>
                        <th>Status</th>
                        <th>Supervisor</th>
                        <th width="150">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rooms as $room)
                        <tr>
                            <td>{{ $room->floor->name ?? 'N/A' }}</td>
                            <td>{{ $room->room_number }}</td>
                            <td>{{ $room->apartment_number }}</td>
                            <td>${{ number_format($room->monthly_rent, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $room->status === 'available' ? 'success' : 'warning' }}">
                                    {{ ucfirst($room->status) }}
                                </span>
                            </td>
                            <td>{{ $room->supervisor->name ?? 'Unassigned' }}</td>
                            <td>
                                <a href="{{ route('admin.rooms.edit', $room) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('admin.rooms.destroy', $room) }}" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No rooms found. <a href="{{ route('admin.rooms.create') }}">Create one now</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $rooms->links() }}
        </div>
    </div>
</div>

<style>
    .table-hover tbody tr:hover {
        background-color: #f5f5f5;
    }
</style>

@endsection
