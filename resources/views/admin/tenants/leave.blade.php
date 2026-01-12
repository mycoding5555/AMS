@extends('layouts.admin')

@section('content')

<div style="background: linear-gradient(135deg, #f5f5f7 0%, #ffffff 100%); border-radius: 20px; padding: 32px; margin-bottom: 32px;">
    <div style="display: flex; justify-content: space-between; align-items: start;">
        <div style="flex: 1;">
            <p style="color: #86868b; font-size: 14px; margin: 0 0 8px 0;">
                <a href="{{ route('admin.tenants.index') }}" style="color: #0071e3; text-decoration: none;">Tenants</a>
                <span style="color: #d5d5d7; margin: 0 8px;">/</span>
                Tenant Leave
            </p>
            <h1 style="font-size: 32px; font-weight: 600; color: #1d1d1f; margin: 0;">Process Tenant Leave</h1>
            <p style="color: #86868b; margin: 8px 0 0 0;">Complete the checkout process for {{ $tenant->name }}</p>
        </div>
    </div>
</div>

<div style="margin-bottom: 24px;">
    <a href="{{ route('admin.tenants.index') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; background: white; color: #0071e3; border: 1px solid #d5d5d7; border-radius: 8px; text-decoration: none; font-weight: 500; font-size: 14px; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f5f5f7'" onmouseout="this.style.backgroundColor='white'">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
        Back to Tenants
    </a>
</div>

@if ($errors->any())
    <div style="background: #fef2f2; border: 1px solid #f87171; border-radius: 12px; padding: 16px; margin-bottom: 24px; color: #991b1b;">
        <div style="display: flex; align-items: start; gap: 12px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0; margin-top: 2px;">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <div>
                <p style="font-weight: 600; margin: 0 0 8px 0;">Errors</p>
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li style="margin-bottom: 4px;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 24px;">
    {{-- Tenant Info Card --}}
    <div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.12);">
        <h3 style="font-weight: 600; color: #1d1d1f; margin: 0 0 20px 0; font-size: 18px; display: flex; align-items: center; gap: 10px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0071e3" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
            </svg>
            Tenant Information
        </h3>
        
        <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 20px;">
            @if($tenant->photo_path)
                <img src="{{ asset('storage/' . $tenant->photo_path) }}" alt="{{ $tenant->name }}" 
                     style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #d5d5d7;">
            @else
                <div style="width: 80px; height: 80px; border-radius: 50%; background-color: #f5f5f7; display: flex; align-items: center; justify-content: center; color: #86868b; font-size: 24px; font-weight: bold; border: 3px solid #d5d5d7;">
                    {{ substr($tenant->name, 0, 1) }}
                </div>
            @endif
            <div>
                <h4 style="margin: 0; color: #1d1d1f; font-size: 20px;">{{ $tenant->name }}</h4>
                <p style="margin: 4px 0 0 0; color: #86868b;">{{ $tenant->email }}</p>
            </div>
        </div>

        <div style="border-top: 1px solid #e5e5e7; padding-top: 16px;">
            <div style="display: grid; gap: 12px;">
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #86868b;">Phone:</span>
                    <span style="color: #1d1d1f; font-weight: 500;">{{ $tenant->phone }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #86868b;">Room:</span>
                    <span style="color: #1d1d1f; font-weight: 500;">{{ $tenant->apartment->apartment_number ?? 'N/A' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #86868b;">Floor:</span>
                    <span style="color: #1d1d1f; font-weight: 500;">{{ $tenant->apartment->floor->name ?? 'N/A' }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: #86868b;">Monthly Rent:</span>
                    <span style="color: #1d1d1f; font-weight: 500;">${{ number_format($monthlyRent, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Stay Duration Card --}}
    <div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.12);">
        <h3 style="font-weight: 600; color: #1d1d1f; margin: 0 0 20px 0; font-size: 18px; display: flex; align-items: center; gap: 10px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0071e3" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            Stay Duration
        </h3>

        <div style="background: linear-gradient(135deg, #0071e3 0%, #0077ed 100%); border-radius: 12px; padding: 20px; color: white; margin-bottom: 20px;">
            <p style="font-size: 13px; opacity: 0.9; margin: 0;">Total Duration</p>
            <h2 style="font-size: 24px; font-weight: 700; margin: 8px 0 0 0;">{{ $stayDuration }}</h2>
            <p style="font-size: 13px; opacity: 0.9; margin: 8px 0 0 0;">{{ $stayDays }} days</p>
        </div>

        <div style="display: grid; gap: 12px;">
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #86868b;">Move-in Date:</span>
                <span style="color: #1d1d1f; font-weight: 500;">{{ $tenant->move_in_date->format('M d, Y') }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #86868b;">Estimated Total Rent:</span>
                <span style="color: #1d1d1f; font-weight: 500;">${{ number_format($estimatedTotalRent, 2) }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Leave Form --}}
<form method="POST" action="{{ route('admin.tenants.leave.process', $tenant) }}" style="margin-top: 24px;">
    @csrf
    
    <div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.12);">
        <h3 style="font-weight: 600; color: #1d1d1f; margin: 0 0 24px 0; font-size: 18px; display: flex; align-items: center; gap: 10px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#0071e3" stroke-width="2">
                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                <polyline points="16 17 21 12 16 7"></polyline>
                <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg>
            Leave Details & Final Charges
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            {{-- Move Out Date --}}
            <div>
                <label for="move_out_date" style="display: block; margin-bottom: 8px; font-weight: 500; color: #1d1d1f;">Move Out Date <span style="color: #ff3b30;">*</span></label>
                <input type="date" id="move_out_date" name="move_out_date" value="{{ old('move_out_date', now()->format('Y-m-d')) }}" min="{{ $tenant->move_in_date->format('Y-m-d') }}"
                       style="width: 100%; padding: 12px 16px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
            </div>

            {{-- Leave Reason --}}
            <div>
                <label for="leave_reason" style="display: block; margin-bottom: 8px; font-weight: 500; color: #1d1d1f;">Leave Reason</label>
                <select id="leave_reason" name="leave_reason" style="width: 100%; padding: 12px 16px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 14px; box-sizing: border-box; background: white;">
                    <option value="">Select reason...</option>
                    <option value="End of lease" {{ old('leave_reason') == 'End of lease' ? 'selected' : '' }}>End of lease</option>
                    <option value="Relocation" {{ old('leave_reason') == 'Relocation' ? 'selected' : '' }}>Relocation</option>
                    <option value="Personal reasons" {{ old('leave_reason') == 'Personal reasons' ? 'selected' : '' }}>Personal reasons</option>
                    <option value="Better opportunity" {{ old('leave_reason') == 'Better opportunity' ? 'selected' : '' }}>Better opportunity</option>
                    <option value="Financial reasons" {{ old('leave_reason') == 'Financial reasons' ? 'selected' : '' }}>Financial reasons</option>
                    <option value="Eviction" {{ old('leave_reason') == 'Eviction' ? 'selected' : '' }}>Eviction</option>
                    <option value="Other" {{ old('leave_reason') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            {{-- Total Rent Paid --}}
            <div>
                <label for="total_rent_paid" style="display: block; margin-bottom: 8px; font-weight: 500; color: #1d1d1f;">Total Rent Paid <span style="color: #ff3b30;">*</span></label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #86868b;">$</span>
                    <input type="number" id="total_rent_paid" name="total_rent_paid" step="0.01" min="0" value="{{ old('total_rent_paid', number_format($estimatedTotalRent, 2, '.', '')) }}"
                           style="width: 100%; padding: 12px 16px 12px 32px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                </div>
            </div>

            {{-- Utility Charges --}}
            <div>
                <label for="final_utility_charges" style="display: block; margin-bottom: 8px; font-weight: 500; color: #1d1d1f;">Utility Charges <span style="color: #ff3b30;">*</span></label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #86868b;">$</span>
                    <input type="number" id="final_utility_charges" name="final_utility_charges" step="0.01" min="0" value="{{ old('final_utility_charges', '0.00') }}"
                           style="width: 100%; padding: 12px 16px 12px 32px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                </div>
                <small style="color: #86868b; font-size: 12px;">Water, electricity, internet, etc.</small>
            </div>

            {{-- Other Charges --}}
            <div>
                <label for="final_other_charges" style="display: block; margin-bottom: 8px; font-weight: 500; color: #1d1d1f;">Other Charges <span style="color: #ff3b30;">*</span></label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #86868b;">$</span>
                    <input type="number" id="final_other_charges" name="final_other_charges" step="0.01" min="0" value="{{ old('final_other_charges', '0.00') }}"
                           style="width: 100%; padding: 12px 16px 12px 32px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
                </div>
                <small style="color: #86868b; font-size: 12px;">Damages, cleaning fees, etc.</small>
            </div>

            {{-- Invoice Notes --}}
            <div style="grid-column: 1 / -1;">
                <label for="invoice_notes" style="display: block; margin-bottom: 8px; font-weight: 500; color: #1d1d1f;">Invoice Notes</label>
                <textarea id="invoice_notes" name="invoice_notes" rows="3" placeholder="Any additional notes for the final invoice..."
                          style="width: 100%; padding: 12px 16px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 14px; box-sizing: border-box; resize: vertical;">{{ old('invoice_notes') }}</textarea>
            </div>
        </div>

        {{-- Total Summary --}}
        <div style="margin-top: 24px; padding: 20px; background: #f5f5f7; border-radius: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 18px; font-weight: 600; color: #1d1d1f;">Total Amount:</span>
                <span id="total_amount" style="font-size: 24px; font-weight: 700; color: #0071e3;">$0.00</span>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div style="margin-top: 24px; display: flex; gap: 12px; flex-wrap: wrap;">
            <button type="submit" style="padding: 14px 28px; background: #ff9500; color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#ff9f0a'" onmouseout="this.style.backgroundColor='#ff9500'">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                Process Leave
            </button>

            <button type="submit" name="generate_invoice" value="1" style="padding: 14px 28px; background: #0071e3; color: white; border: none; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#0077ed'" onmouseout="this.style.backgroundColor='#0071e3'">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                Process & Download Invoice
            </button>

            <a href="{{ route('admin.tenants.index') }}" style="padding: 14px 28px; background: #f5f5f7; color: #1d1d1f; border: 1px solid #d5d5d7; border-radius: 8px; font-weight: 500; font-size: 14px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#e8e8ea'" onmouseout="this.style.backgroundColor='#f5f5f7'">
                Cancel
            </a>
        </div>
    </div>
</form>

<script>
    function calculateTotal() {
        const rent = parseFloat(document.getElementById('total_rent_paid').value) || 0;
        const utility = parseFloat(document.getElementById('final_utility_charges').value) || 0;
        const other = parseFloat(document.getElementById('final_other_charges').value) || 0;
        const total = rent + utility + other;
        document.getElementById('total_amount').textContent = '$' + total.toFixed(2);
    }

    document.getElementById('total_rent_paid').addEventListener('input', calculateTotal);
    document.getElementById('final_utility_charges').addEventListener('input', calculateTotal);
    document.getElementById('final_other_charges').addEventListener('input', calculateTotal);

    // Calculate on page load
    calculateTotal();
</script>

@endsection
