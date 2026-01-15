@extends('layouts.admin')

@section('content')

<div style="background: linear-gradient(135deg, #f5f5f7 0%, #ffffff 100%); border-radius: 20px; padding: 32px; margin-bottom: 32px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 32px; font-weight: 600; color: #1d1d1f; margin: 0;">Edit Fiscal Period</h1>
            <p style="color: #86868b; margin: 8px 0 0 0;">Update fiscal period details</p>
        </div>
        <a href="{{ route('admin.fiscal-periods.show', $fiscalPeriod) }}" style="padding: 12px 24px; background: #f5f5f7; color: #1d1d1f; border-radius: 8px; text-decoration: none; font-weight: 600;">
            <i class="bi bi-arrow-left me-2"></i>Back to Period
        </a>
    </div>
</div>

<div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 32px;">
    <form method="POST" action="{{ route('admin.fiscal-periods.update', $fiscalPeriod) }}">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
            {{-- Period Name --}}
            <div style="grid-column: span 2;">
                <label style="display: block; color: #1d1d1f; font-weight: 600; margin-bottom: 8px;">Period Name *</label>
                <input type="text" name="name" value="{{ old('name', $fiscalPeriod->name) }}" 
                    placeholder="e.g., Fiscal Year 2026, Q1 2026"
                    style="width: 100%; padding: 12px 16px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 15px;"
                    required>
                @error('name')
                    <p style="color: #ff3b30; font-size: 13px; margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Opening Date --}}
            <div>
                <label style="display: block; color: #1d1d1f; font-weight: 600; margin-bottom: 8px;">Opening Date *</label>
                <input type="date" name="opening_date" value="{{ old('opening_date', $fiscalPeriod->opening_date->format('Y-m-d')) }}"
                    style="width: 100%; padding: 12px 16px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 15px;"
                    required>
                @error('opening_date')
                    <p style="color: #ff3b30; font-size: 13px; margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Closing Date --}}
            <div>
                <label style="display: block; color: #1d1d1f; font-weight: 600; margin-bottom: 8px;">Closing Date *</label>
                <input type="date" name="closing_date" value="{{ old('closing_date', $fiscalPeriod->closing_date->format('Y-m-d')) }}"
                    style="width: 100%; padding: 12px 16px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 15px;"
                    required>
                @error('closing_date')
                    <p style="color: #ff3b30; font-size: 13px; margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Opening Balance --}}
            <div>
                <label style="display: block; color: #1d1d1f; font-weight: 600; margin-bottom: 8px;">Opening Balance ($) *</label>
                <input type="number" name="opening_balance" step="0.01" 
                    value="{{ old('opening_balance', $fiscalPeriod->opening_balance) }}"
                    style="width: 100%; padding: 12px 16px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 15px;"
                    required>
                @error('opening_balance')
                    <p style="color: #ff3b30; font-size: 13px; margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Notes --}}
            <div style="grid-column: span 2;">
                <label style="display: block; color: #1d1d1f; font-weight: 600; margin-bottom: 8px;">Notes</label>
                <textarea name="notes" rows="4" 
                    placeholder="Optional notes about this fiscal period..."
                    style="width: 100%; padding: 12px 16px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 15px; resize: vertical;">{{ old('notes', $fiscalPeriod->notes) }}</textarea>
                @error('notes')
                    <p style="color: #ff3b30; font-size: 13px; margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 32px;">
            <button type="submit" style="padding: 14px 32px; background: #0071e3; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 15px;">
                Update Fiscal Period
            </button>
            <a href="{{ route('admin.fiscal-periods.show', $fiscalPeriod) }}" style="padding: 14px 32px; background: #f5f5f7; color: #1d1d1f; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 15px;">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection
