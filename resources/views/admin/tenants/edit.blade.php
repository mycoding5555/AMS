@extends('layouts.admin')

@section('content')

<div class="mb-4">
    <h4>Edit Tenant Information</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.tenants.index') }}">Tenants</a></li>
            <li class="breadcrumb-item active">Edit Tenant</li>
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
        <form method="POST" action="{{ route('admin.tenants.update', $tenant) }}" class="row g-3" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="col-md-6">
                <label for="apartment_id" class="form-label">Select Room <span class="text-danger">*</span></label>
                <select name="apartment_id" id="apartment_id" class="form-select @error('apartment_id') is-invalid @enderror" required>
                    <option value="">-- Select Room --</option>
                    @foreach($apartments as $apartment)
                        <option value="{{ $apartment->id }}" {{ $tenant->apartment_id == $apartment->id ? 'selected' : '' }}>
                            {{ $apartment->apartment_number }} (Floor: {{ $apartment->floor->name ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
                @error('apartment_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="name" class="form-label">Tenant Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                       placeholder="John Doe" value="{{ old('name', $tenant->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                       placeholder="john@example.com" value="{{ old('email', $tenant->email) }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                       placeholder="(555) 123-4567" value="{{ old('phone', $tenant->phone) }}" required>
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-12">
                <label for="address" class="form-label">Address (Optional)</label>
                <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" 
                       placeholder="Previous address" value="{{ old('address', $tenant->address) }}">
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="move_in_date" class="form-label">Move-In Date <span class="text-danger">*</span></label>
                <input type="date" name="move_in_date" id="move_in_date" class="form-control @error('move_in_date') is-invalid @enderror" 
                       value="{{ old('move_in_date', $tenant->move_in_date->format('Y-m-d')) }}" required>
                @error('move_in_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="move_out_date" class="form-label">Move-Out Date (Optional)</label>
                <input type="date" name="move_out_date" id="move_out_date" class="form-control @error('move_out_date') is-invalid @enderror" 
                       value="{{ old('move_out_date', $tenant->move_out_date ? $tenant->move_out_date->format('Y-m-d') : '') }}">
                @error('move_out_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="">-- Select Status --</option>
                    <option value="active" {{ $tenant->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ $tenant->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="moved_out" {{ $tenant->status == 'moved_out' ? 'selected' : '' }}>Moved Out</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6"></div>

            <div class="col-md-12">
                <label for="notes" class="form-label">Notes (Optional)</label>
                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" 
                          rows="3" placeholder="Additional information...">{{ old('notes', $tenant->notes) }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="photo" class="form-label">Profile Photo (Optional)</label>
                @if($tenant->photo_path)
                    <div class="mb-2">
                        <img src="{{ Storage::url($tenant->photo_path) }}" alt="Current photo" style="max-width: 150px; border-radius: 8px;">
                        <p class="text-muted small">Current photo</p>
                    </div>
                @endif
                <input type="file" name="photo" id="photo" class="form-control @error('photo') is-invalid @enderror" 
                       accept="image/jpeg,image/png,image/jpg,image/gif">
                <small class="text-muted">Max 2MB. Accepted formats: JPEG, PNG, JPG, GIF</small>
                @error('photo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="document" class="form-label">Document/ID (PDF) (Optional)</label>
                @if($tenant->document_path)
                    <div class="mb-2">
                        <a href="{{ Storage::url($tenant->document_path) }}" target="_blank" class="btn btn-sm btn-info">
                            <i class="bi bi-download"></i> View Current Document
                        </a>
                    </div>
                @endif
                <input type="file" name="document" id="document" class="form-control @error('document') is-invalid @enderror" 
                       accept="application/pdf">
                <small class="text-muted">Max 5MB. PDF files only</small>
                @error('document')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Update Tenant
                    </button>
                    <a href="{{ route('admin.tenants.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Cancel
                    </a>
                    <form method="POST" action="{{ route('admin.tenants.destroy', $tenant) }}" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this tenant?');">
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
