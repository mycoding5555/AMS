@extends('layouts.admin')

@section('content')

<div class="mb-4">
    <h4>Add New Tenant</h4>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.tenants.index') }}">Tenants</a></li>
            <li class="breadcrumb-item active">Add New Tenant</li>
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
        <form method="POST" action="{{ route('admin.tenants.store') }}" class="row g-3" enctype="multipart/form-data">
            @csrf

            <div class="col-md-6">
                <label for="apartment_id" class="form-label">Select Room <span class="text-danger">*</span></label>
                <select name="apartment_id" id="apartment_id" class="form-select @error('apartment_id') is-invalid @enderror" required>
                    <option value="">-- Select Room --</option>
                    @foreach($apartments as $apartment)
                        <option value="{{ $apartment->id }}" 
                                data-tenant="{{ json_encode($apartment->tenants()->where('status', 'active')->first() ? [
                                    'id' => $apartment->tenants()->where('status', 'active')->first()->id,
                                    'name' => $apartment->tenants()->where('status', 'active')->first()->name,
                                    'photo_path' => $apartment->tenants()->where('status', 'active')->first()->photo_path,
                                    'document_path' => $apartment->tenants()->where('status', 'active')->first()->document_path,
                                    'email' => $apartment->tenants()->where('status', 'active')->first()->email,
                                    'phone' => $apartment->tenants()->where('status', 'active')->first()->phone
                                ] : null) }}"
                                {{ (old('apartment_id') ?? $selectedApartmentId) == $apartment->id ? 'selected' : '' }}>
                            {{ $apartment->apartment_number }} (Floor: {{ $apartment->floor->name ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
                @error('apartment_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Tenant Details Section -->
            <div class="col-md-12" id="tenantDetailsSection" style="display: none;">
                <div class="card border-info mb-3">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">Current Tenant Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <img id="tenantPhoto" src="" alt="Tenant Photo" 
                                     style="width: 120px; height: 120px; border-radius: 8px; object-fit: cover; display: none;">
                                <div id="noPhotoPlaceholder" style="width: 120px; height: 120px; background-color: #e9ecef; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #6c757d; margin: 0 auto;">
                                    <i class="bi bi-image" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <h6><strong id="tenantName"></strong></h6>
                                <p class="mb-2"><strong>Email:</strong> <span id="tenantEmail"></span></p>
                                <p class="mb-2"><strong>Phone:</strong> <span id="tenantPhone"></span></p>
                                <div id="documentSection" style="display: none;">
                                    <p class="mb-0"><strong>Document:</strong> <a id="documentLink" href="" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-file-pdf"></i> View PDF
                                    </a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="noTenantSection" class="col-md-12" style="display: none;">
                <div class="alert alert-success mb-3">
                    <i class="bi bi-check-circle"></i> This room is <strong>Available</strong>
                </div>
            </div>

            <div class="col-md-6">
                <label for="name" class="form-label">Tenant Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                       placeholder="John Doe" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                       placeholder="john@example.com" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" 
                       placeholder="(555) 123-4567" value="{{ old('phone') }}" required>
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-12">
                <label for="address" class="form-label">Address (Optional)</label>
                <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" 
                       placeholder="Previous address" value="{{ old('address') }}">
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="move_in_date" class="form-label">Move-In Date <span class="text-danger">*</span></label>
                <input type="date" name="move_in_date" id="move_in_date" class="form-control @error('move_in_date') is-invalid @enderror" 
                       value="{{ old('move_in_date') }}" required>
                @error('move_in_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="move_out_date" class="form-label">Move-Out Date (Optional)</label>
                <input type="date" name="move_out_date" id="move_out_date" class="form-control @error('move_out_date') is-invalid @enderror" 
                       value="{{ old('move_out_date') }}">
                @error('move_out_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="">-- Select Status --</option>
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="moved_out" {{ old('status') == 'moved_out' ? 'selected' : '' }}>Moved Out</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6"></div>

            <div class="col-md-12">
                <label for="notes" class="form-label">Notes (Optional)</label>
                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" 
                          rows="3" placeholder="Additional information...">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="photo" class="form-label">Profile Photo (Optional)</label>
                <input type="file" name="photo" id="photo" class="form-control @error('photo') is-invalid @enderror" 
                       accept="image/jpeg,image/png,image/jpg,image/gif">
                <small class="text-muted">Max 2MB. Accepted formats: JPEG, PNG, JPG, GIF</small>
                @error('photo')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="document" class="form-label">Document/ID (PDF) (Optional)</label>
                <input type="file" name="document" id="document" class="form-control @error('document') is-invalid @enderror" 
                       accept="application/pdf">
                <small class="text-muted">Max 5MB. PDF files only</small>
                @error('document')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-12">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Add Tenant
                    </button>
                    <a href="{{ route('admin.tenants.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const apartmentSelect = document.getElementById('apartment_id');
    const tenantDetailsSection = document.getElementById('tenantDetailsSection');
    const noTenantSection = document.getElementById('noTenantSection');
    const tenantPhoto = document.getElementById('tenantPhoto');
    const noPhotoPlaceholder = document.getElementById('noPhotoPlaceholder');
    const tenantName = document.getElementById('tenantName');
    const tenantEmail = document.getElementById('tenantEmail');
    const tenantPhone = document.getElementById('tenantPhone');
    const documentSection = document.getElementById('documentSection');
    const documentLink = document.getElementById('documentLink');

    function updateTenantDetails() {
        const selectedOption = apartmentSelect.options[apartmentSelect.selectedIndex];
        const tenantData = selectedOption.getAttribute('data-tenant');
        
        if (!tenantData || tenantData === 'null') {
            // No tenant, show available message
            tenantDetailsSection.style.display = 'none';
            noTenantSection.style.display = 'block';
        } else {
            const tenant = JSON.parse(tenantData);
            
            // Update tenant details
            tenantName.textContent = tenant.name;
            tenantEmail.textContent = tenant.email;
            tenantPhone.textContent = tenant.phone;
            
            // Handle photo
            if (tenant.photo_path) {
                tenantPhoto.src = '/storage/' + tenant.photo_path;
                tenantPhoto.style.display = 'block';
                noPhotoPlaceholder.style.display = 'none';
            } else {
                tenantPhoto.style.display = 'none';
                noPhotoPlaceholder.style.display = 'flex';
            }
            
            // Handle document
            if (tenant.document_path) {
                documentLink.href = '/storage/' + tenant.document_path;
                documentSection.style.display = 'block';
            } else {
                documentSection.style.display = 'none';
            }
            
            tenantDetailsSection.style.display = 'block';
            noTenantSection.style.display = 'none';
        }
    }

    // Update on change
    apartmentSelect.addEventListener('change', updateTenantDetails);
    
    // Initial update - will be called automatically after page load
    updateTenantDetails();
    
    // Trigger change event to ensure proper initialization if a room was pre-selected
    if (apartmentSelect.value) {
        apartmentSelect.dispatchEvent(new Event('change'));
    }
});
</script>

@endsection
