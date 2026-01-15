@extends('layouts.admin')

@section('content')

<div style="background: linear-gradient(135deg, #f5f5f7 0%, #ffffff 100%); border-radius: 20px; padding: 32px; margin-bottom: 32px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 32px; font-weight: 600; color: #1d1d1f; margin: 0;">Create Fiscal Period</h1>
            <p style="color: #86868b; margin: 8px 0 0 0;">Set up a new business period with opening balance</p>
        </div>
        <a href="{{ route('admin.fiscal-periods.index') }}" style="padding: 12px 24px; background: #f5f5f7; color: #1d1d1f; border-radius: 8px; text-decoration: none; font-weight: 600;">
            <i class="bi bi-arrow-left me-2"></i>Back to Periods
        </a>
    </div>
</div>

@if($lastPeriod)
<div style="background: #dbeafe; border: 1px solid #3b82f6; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
    <div style="display: flex; align-items: center; gap: 12px;">
        <i class="bi bi-info-circle" style="font-size: 24px; color: #1e40af;"></i>
        <div>
            <p style="font-weight: 600; color: #1e40af; margin: 0;">Previous Period: {{ $lastPeriod->name }}</p>
            <p style="color: #1e40af; margin: 4px 0 0 0; font-size: 14px;">
                Closing balance was ${{ number_format($lastPeriod->closing_balance, 2) }} - suggested as opening balance below.
            </p>
        </div>
    </div>
</div>
@endif

<div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 32px;">
    <form method="POST" action="{{ route('admin.fiscal-periods.store') }}">
        @csrf
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
            {{-- Period Name --}}
            <div style="grid-column: span 2;">
                <label style="display: block; color: #1d1d1f; font-weight: 600; margin-bottom: 8px;">Period Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" 
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
                <input type="date" name="opening_date" value="{{ old('opening_date', now()->format('Y-m-d')) }}"
                    style="width: 100%; padding: 12px 16px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 15px;"
                    required>
                @error('opening_date')
                    <p style="color: #ff3b30; font-size: 13px; margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Closing Date --}}
            <div>
                <label style="display: block; color: #1d1d1f; font-weight: 600; margin-bottom: 8px;">Closing Date *</label>
                <input type="date" name="closing_date" value="{{ old('closing_date', now()->addYear()->format('Y-m-d')) }}"
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
                    value="{{ old('opening_balance', $suggestedOpeningBalance) }}"
                    style="width: 100%; padding: 12px 16px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 15px;"
                    required>
                <p style="color: #86868b; font-size: 13px; margin-top: 4px;">The starting balance for this fiscal period.</p>
                @error('opening_balance')
                    <p style="color: #ff3b30; font-size: 13px; margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Set as Current --}}
            <div>
                <label style="display: block; color: #1d1d1f; font-weight: 600; margin-bottom: 8px;">Set as Current Period</label>
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="set_as_current" value="1" {{ old('set_as_current') ? 'checked' : '' }}
                        style="width: 20px; height: 20px;">
                    <span style="color: #1d1d1f;">Make this the active fiscal period</span>
                </label>
            </div>
            
            {{-- Carry Forward Balance Sheet --}}
            <div>
                <label style="display: block; color: #1d1d1f; font-weight: 600; margin-bottom: 8px;">Carry Forward Balance Sheet</label>
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="carry_forward_balance_sheet" value="1" {{ old('carry_forward_balance_sheet', $lastPeriod ? 1 : 0) ? 'checked' : '' }}
                        style="width: 20px; height: 20px;" {{ $lastPeriod ? '' : 'disabled' }}>
                    <span style="color: #1d1d1f;">Copy assets, liabilities & equity from previous period</span>
                </label>
                @if(!$lastPeriod)
                    <p style="color: #86868b; font-size: 13px; margin-top: 4px;">No previous closed period to carry forward from.</p>
                @endif
            </div>

            {{-- Notes --}}
            <div style="grid-column: span 2;">
                <label style="display: block; color: #1d1d1f; font-weight: 600; margin-bottom: 8px;">Notes</label>
                <textarea name="notes" rows="4" 
                    placeholder="Optional notes about this fiscal period..."
                    style="width: 100%; padding: 12px 16px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 15px; resize: vertical;">{{ old('notes') }}</textarea>
                @error('notes')
                    <p style="color: #ff3b30; font-size: 13px; margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Information Box --}}
        <div style="background: #f5f5f7; border-radius: 12px; padding: 20px; margin-top: 24px;">
            <h4 style="font-weight: 600; color: #1d1d1f; margin: 0 0 12px 0;">
                <i class="bi bi-info-circle me-2"></i>About Fiscal Periods
            </h4>
            <ul style="color: #86868b; margin: 0; padding-left: 20px; line-height: 1.8;">
                <li>A fiscal period tracks your business finances from opening to closing date.</li>
                <li>All revenue and expenses during this period will be recorded against it.</li>
                <li>At the closing date, you can finalize and export the closing balance.</li>
                <li>The closing balance becomes the opening balance for the next period.</li>
                <li><strong>Carry Forward</strong> copies your assets, liabilities, and equity to the new period.</li>
            </ul>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 32px;">
            <button type="submit" style="padding: 14px 32px; background: #0071e3; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 15px;">
                Create Fiscal Period
            </button>
            <a href="{{ route('admin.fiscal-periods.index') }}" style="padding: 14px 32px; background: #f5f5f7; color: #1d1d1f; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 15px;">
                Cancel
            </a>
        </div>
    </form>
</div>

@endsection
