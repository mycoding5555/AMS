@extends('layouts.admin')

@section('content')

<h4 class="mb-4">User Management</h4>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th width="220">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
                        @csrf
                        @method('PUT')

                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>

                        <td>
                            <select name="role" class="form-select form-select-sm">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </td>

                        <td>
                            <select name="status" class="form-select form-select-sm">
                                <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="suspended" {{ $user->status === 'suspended' ? 'selected' : '' }}>
                                    Suspended
                                </option>
                            </select>
                        </td>

                        <td>
                            <button class="btn btn-sm btn-primary">
                                Save Changes
                            </button>
                        </td>
                    </form>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $users->links() }}
    </div>
</div>

@endsection
