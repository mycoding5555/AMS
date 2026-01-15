@extends('layouts.admin')

@section('content')

<div style="background: linear-gradient(135deg, #f5f5f7 0%, #ffffff 100%); border-radius: 20px; padding: 32px; margin-bottom: 32px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 32px; font-weight: 600; color: #1d1d1f; margin: 0;">Add Balance Sheet Item</h1>
            <p style="color: #86868b; margin: 8px 0 0 0;">Record assets, liabilities, or equity</p>
        </div>
        <a href="{{ route('admin.balance-sheet.index') }}" style="padding: 12px 24px; background: #f5f5f7; color: #1d1d1f; border-radius: 8px; text-decoration: none; font-weight: 600;">
            <i class="bi bi-arrow-left me-2"></i>Back to Balance Sheet
        </a>
    </div>
</div>

<div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 32px;">
    <form method="POST" action="{{ route('admin.balance-sheet.store') }}">
        @csrf
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
            {{-- Fiscal Period --}}
            <div>
                <label style="display: block; color: #1d1d1f; font-weight: 600; margin-bottom: 8px;">Fiscal Period *</label>
                <select name="fiscal_period_id" id="fiscal_period_id" required
                    style="width: 100%; padding: 12px 16px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 15px;">
                    <option value="">Select Period</option>
                    @foreach($fiscalPeriods as $period)
                        <option value="{{ $period->id }}" {{ (old('fiscal_period_id', $defaultPeriodId) == $period->id) ? 'selected' : '' }}>
                            {{ $period->name }} {{ $period->is_current ? '(Current)' : '' }}
                        </option>
                    @endforeach
                </select>
                @error('fiscal_period_id')
                    <p style="color: #ff3b30; font-size: 13px; margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Item Type --}}
            <div>
                <label style="display: block; color: #1d1d1f; font-weight: 600; margin-bottom: 8px;">Item Type *</label>
                <select name="item_type" id="item_type" required
                    style="width: 100%; padding: 12px 16px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 15px;">
                    <option value="">Select Type</option>
                    @foreach($itemTypes as $key => $label)
                        <option value="{{ $key }}" {{ old('item_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('item_type')
                    <p style="color: #ff3b30; font-size: 13px; margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Sub Type --}}
            <div>
                <label style="display: block; color: #1d1d1f; font-weight: 600; margin-bottom: 8px;">Sub Type *</label>
                <select name="sub_type" id="sub_type" required
                    style="width: 100%; padding: 12px 16px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 15px;">
                    <option value="">Select Sub Type</option>
                    @foreach($subTypes as $key => $label)
                        <option value="{{ $key }}" {{ old('sub_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('sub_type')
                    <p style="color: #ff3b30; font-size: 13px; margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Common Item Selection --}}
            <div>
                <label style="display: block; color: #1d1d1f; font-weight: 600; margin-bottom: 8px;">Quick Select (Optional)</label>
                <select id="quick_select" style="width: 100%; padding: 12px 16px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 15px;">
                    <option value="">Choose common item...</option>
                </select>
                <p style="color: #86868b; font-size: 13px; margin-top: 4px;">Select a predefined item to auto-fill the name.</p>
            </div>

            {{-- Item Name --}}
            <div>
                <label style="display: block; color: #1d1d1f; font-weight: 600; margin-bottom: 8px;">Item Name *</label>
                <input type="text" name="name" id="item_name" value="{{ old('name') }}"
                    placeholder="e.g., Cash, Building, Accounts Payable"
                    style="width: 100%; padding: 12px 16px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 15px;"
                    required>
                @error('name')
                    <p style="color: #ff3b30; font-size: 13px; margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label style="display: block; color: #1d1d1f; font-weight: 600; margin-bottom: 8px;">Description</label>
                <input type="text" name="description" value="{{ old('description') }}"
                    placeholder="Brief description"
                    style="width: 100%; padding: 12px 16px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 15px;">
                @error('description')
                    <p style="color: #ff3b30; font-size: 13px; margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Amount --}}
            <div>
                <label style="display: block; color: #1d1d1f; font-weight: 600; margin-bottom: 8px;">Amount ($) *</label>
                <input type="number" name="amount" step="0.01" min="0" value="{{ old('amount') }}"
                    placeholder="0.00"
                    style="width: 100%; padding: 12px 16px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 15px;"
                    required>
                @error('amount')
                    <p style="color: #ff3b30; font-size: 13px; margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- As Of Date --}}
            <div>
                <label style="display: block; color: #1d1d1f; font-weight: 600; margin-bottom: 8px;">As Of Date *</label>
                <input type="date" name="as_of_date" value="{{ old('as_of_date', now()->format('Y-m-d')) }}"
                    style="width: 100%; padding: 12px 16px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 15px;"
                    required>
                @error('as_of_date')
                    <p style="color: #ff3b30; font-size: 13px; margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Reference Number --}}
            <div>
                <label style="display: block; color: #1d1d1f; font-weight: 600; margin-bottom: 8px;">Reference Number</label>
                <input type="text" name="reference_number" value="{{ old('reference_number') }}"
                    placeholder="Optional reference"
                    style="width: 100%; padding: 12px 16px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 15px;">
                @error('reference_number')
                    <p style="color: #ff3b30; font-size: 13px; margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>

            {{-- Notes --}}
            <div style="grid-column: span 2;">
                <label style="display: block; color: #1d1d1f; font-weight: 600; margin-bottom: 8px;">Notes</label>
                <textarea name="notes" rows="3" 
                    placeholder="Additional notes..."
                    style="width: 100%; padding: 12px 16px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 15px; resize: vertical;">{{ old('notes') }}</textarea>
                @error('notes')
                    <p style="color: #ff3b30; font-size: 13px; margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div style="display: flex; gap: 12px; margin-top: 32px;">
            <button type="submit" style="padding: 14px 32px; background: #0071e3; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 15px;">
                Add Item
            </button>
            <a href="{{ route('admin.balance-sheet.index') }}" style="padding: 14px 32px; background: #f5f5f7; color: #1d1d1f; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 15px;">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
const predefinedItems = @json($predefinedItems);

document.getElementById('item_type').addEventListener('change', function() {
    updateSubTypes();
    updateQuickSelect();
});

document.getElementById('sub_type').addEventListener('change', function() {
    updateQuickSelect();
});

document.getElementById('quick_select').addEventListener('change', function() {
    const nameInput = document.getElementById('item_name');
    if (this.value) {
        nameInput.value = this.options[this.selectedIndex].text;
    }
});

function updateSubTypes() {
    const itemType = document.getElementById('item_type').value;
    const subTypeSelect = document.getElementById('sub_type');
    
    // Clear current options
    subTypeSelect.innerHTML = '<option value="">Select Sub Type</option>';
    
    const subTypes = {
        'asset': {
            'current_asset': 'Current Asset',
            'fixed_asset': 'Fixed Asset'
        },
        'liability': {
            'current_liability': 'Current Liability',
            'long_term_liability': 'Long-term Liability'
        },
        'equity': {
            'owner_equity': "Owner's Equity",
            'retained_earnings': 'Retained Earnings'
        }
    };
    
    if (itemType && subTypes[itemType]) {
        for (const [key, label] of Object.entries(subTypes[itemType])) {
            const option = document.createElement('option');
            option.value = key;
            option.textContent = label;
            subTypeSelect.appendChild(option);
        }
    }
}

function updateQuickSelect() {
    const itemType = document.getElementById('item_type').value;
    const subType = document.getElementById('sub_type').value;
    const quickSelect = document.getElementById('quick_select');
    
    // Clear current options
    quickSelect.innerHTML = '<option value="">Choose common item...</option>';
    
    if (itemType && subType && predefinedItems[itemType] && predefinedItems[itemType][subType]) {
        const items = predefinedItems[itemType][subType];
        for (const [key, label] of Object.entries(items)) {
            const option = document.createElement('option');
            option.value = key;
            option.textContent = label;
            quickSelect.appendChild(option);
        }
    }
}
</script>

@endsection
