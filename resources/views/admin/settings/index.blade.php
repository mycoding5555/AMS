@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-gear me-2"></i>{{ __('app.system_settings') }}
                    </h4>
                </div>
                <div class="card-body">
                    {{-- Success Alert --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Error Alert --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle me-2"></i>{{ __('app.error') }}:
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf

                        {{-- Application Settings --}}
                        <div class="mb-4">
                            <h5 class="border-bottom pb-3 mb-3">
                                <i class="bi bi-app me-2"></i>{{ __('app.application_settings') }}
                            </h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="app_name" class="form-label">{{ __('app.app_name') }}</label>
                                    <input type="text" class="form-control @error('app_name') is-invalid @enderror"
                                        id="app_name" name="app_name" value="{{ old('app_name', $appSettings['app_name']) }}" required>
                                    @error('app_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="language" class="form-label">{{ __('app.language') }}</label>
                                    <select class="form-select @error('language') is-invalid @enderror" id="language" name="language" required>
                                        <option value="en" {{ old('language', $appSettings['language']) === 'en' ? 'selected' : '' }}>
                                            English
                                        </option>
                                        <option value="km" {{ old('language', $appSettings['language']) === 'km' ? 'selected' : '' }}>
                                            ភាសាខ្មែរ (Khmer)
                                        </option>
                                    </select>
                                    @error('language')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="theme" class="form-label">{{ __('app.theme') }}</label>
                                    <select class="form-select @error('theme') is-invalid @enderror" id="theme" name="theme" required>
                                        <option value="light" {{ old('theme', $appSettings['theme']) === 'light' ? 'selected' : '' }}>
                                            ☀️ {{ __('app.light') }}
                                        </option>
                                        <option value="dark" {{ old('theme', $appSettings['theme']) === 'dark' ? 'selected' : '' }}>
                                            🌙 {{ __('app.dark') }}
                                        </option>
                                    </select>
                                    @error('theme')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="timezone" class="form-label">{{ __('app.timezone') }}</label>
                                    <select class="form-select @error('timezone') is-invalid @enderror" id="timezone" name="timezone" required>
                                        <option value="UTC" {{ old('timezone', $appSettings['timezone']) === 'UTC' ? 'selected' : '' }}>UTC</option>
                                        <option value="America/New_York" {{ old('timezone', $appSettings['timezone']) === 'America/New_York' ? 'selected' : '' }}>America/New_York</option>
                                        <option value="Europe/London" {{ old('timezone', $appSettings['timezone']) === 'Europe/London' ? 'selected' : '' }}>Europe/London</option>
                                        <option value="Asia/Bangkok" {{ old('timezone', $appSettings['timezone']) === 'Asia/Bangkok' ? 'selected' : '' }}>Asia/Bangkok</option>
                                        <option value="Asia/Phnom_Penh" {{ old('timezone', $appSettings['timezone']) === 'Asia/Phnom_Penh' ? 'selected' : '' }}>Asia/Phnom_Penh</option>
                                        <option value="Australia/Sydney" {{ old('timezone', $appSettings['timezone']) === 'Australia/Sydney' ? 'selected' : '' }}>Australia/Sydney</option>
                                    </select>
                                    @error('timezone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Date Range Settings --}}
                        <div class="mb-4">
                            <h5 class="border-bottom pb-3 mb-3">
                                <i class="bi bi-calendar-range me-2"></i>{{ __('app.date_range_settings') }}
                            </h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="app_start_date" class="form-label">{{ __('app.start_date') }}</label>
                                    <input type="date" class="form-control @error('app_start_date') is-invalid @enderror"
                                        id="app_start_date" name="app_start_date" value="{{ old('app_start_date', $appSettings['app_start_date']) }}">
                                    <small class="text-muted">When the apartment management system started operations</small>
                                    @error('app_start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="app_close_date" class="form-label">{{ __('app.close_date') }}</label>
                                    <input type="date" class="form-control @error('app_close_date') is-invalid @enderror"
                                        id="app_close_date" name="app_close_date" value="{{ old('app_close_date', $appSettings['app_close_date']) }}">
                                    <small class="text-muted">When the apartment management system will close or end operations</small>
                                    @error('app_close_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Company Information --}}
                        <div class="mb-4">
                            <h5 class="border-bottom pb-3 mb-3">
                                <i class="bi bi-building me-2"></i>{{ __('app.company_information') }}
                            </h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="company_name" class="form-label">{{ __('app.company_name') }}</label>
                                    <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                        id="company_name" name="company_name" value="{{ old('company_name', $appSettings['company_name']) }}">
                                    @error('company_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="company_phone" class="form-label">{{ __('app.company_phone') }}</label>
                                    <input type="tel" class="form-control @error('company_phone') is-invalid @enderror"
                                        id="company_phone" name="company_phone" value="{{ old('company_phone', $appSettings['company_phone']) }}">
                                    @error('company_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="company_email" class="form-label">{{ __('app.company_email') }}</label>
                                    <input type="email" class="form-control @error('company_email') is-invalid @enderror"
                                        id="company_email" name="company_email" value="{{ old('company_email', $appSettings['company_email']) }}">
                                    @error('company_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="company_address" class="form-label">{{ __('app.company_address') }}</label>
                                    <textarea class="form-control @error('company_address') is-invalid @enderror"
                                        id="company_address" name="company_address" rows="3">{{ old('company_address', $appSettings['company_address']) }}</textarea>
                                    @error('company_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Financial Settings --}}
                        <div class="mb-4">
                            <h5 class="border-bottom pb-3 mb-3">
                                <i class="bi bi-currency-dollar me-2"></i>{{ __('app.financial_settings') }}
                            </h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="currency" class="form-label">{{ __('app.currency') }}</label>
                                    <select class="form-select @error('currency') is-invalid @enderror" id="currency" name="currency" required>
                                        <option value="USD" {{ old('currency', $appSettings['currency']) === 'USD' ? 'selected' : '' }}>USD - United States Dollar</option>
                                        <option value="EUR" {{ old('currency', $appSettings['currency']) === 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                        <option value="GBP" {{ old('currency', $appSettings['currency']) === 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                        <option value="KHR" {{ old('currency', $appSettings['currency']) === 'KHR' ? 'selected' : '' }}>KHR - Cambodian Riel</option>
                                        <option value="JPY" {{ old('currency', $appSettings['currency']) === 'JPY' ? 'selected' : '' }}>JPY - Japanese Yen</option>
                                        <option value="AUD" {{ old('currency', $appSettings['currency']) === 'AUD' ? 'selected' : '' }}>AUD - Australian Dollar</option>
                                    </select>
                                    @error('currency')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="tax_rate" class="form-label">{{ __('app.tax_rate') }} (%)</label>
                                    <input type="number" class="form-control @error('tax_rate') is-invalid @enderror"
                                        id="tax_rate" name="tax_rate" step="0.01" min="0" max="100"
                                        value="{{ old('tax_rate', $appSettings['tax_rate']) }}">
                                    @error('tax_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- System Settings --}}
                        <div class="mb-4">
                            <h5 class="border-bottom pb-3 mb-3">
                                <i class="bi bi-sliders me-2"></i>{{ __('app.system_settings') }}
                            </h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="maintenance_mode" class="form-label">{{ __('app.maintenance_mode') }}</label>
                                    <select class="form-select @error('maintenance_mode') is-invalid @enderror" id="maintenance_mode" name="maintenance_mode" required>
                                        <option value="off" {{ old('maintenance_mode', $appSettings['maintenance_mode']) === 'off' ? 'selected' : '' }}>
                                            {{ __('app.off') }}
                                        </option>
                                        <option value="on" {{ old('maintenance_mode', $appSettings['maintenance_mode']) === 'on' ? 'selected' : '' }}>
                                            {{ __('app.on') }}
                                        </option>
                                    </select>
                                    <small class="text-muted">Enable to restrict user access during system maintenance</small>
                                    @error('maintenance_mode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>{{ __('app.save_settings') }}
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>{{ __('app.back') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        border-radius: 8px;
    }
    
    .card-header {
        border-radius: 8px 8px 0 0;
        padding: 1.5rem;
    }
    
    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #333;
    }
    
    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    h5 {
        color: #333;
        font-weight: 600;
    }
    
    .border-bottom {
        border-color: #e9ecef !important;
    }
</style>
@endsection
