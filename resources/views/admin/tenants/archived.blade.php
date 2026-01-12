@extends('layouts.admin')

@section('content')

<div style="background: linear-gradient(135deg, #f5f5f7 0%, #ffffff 100%); border-radius: 20px; padding: 32px; margin-bottom: 32px;">
    <div style="display: flex; justify-content: space-between; align-items: start;">
        <div style="flex: 1;">
            <h1 style="font-size: 32px; font-weight: 600; color: #1d1d1f; margin: 0;">Archived Tenants</h1>
            <p style="color: #86868b; margin: 8px 0 0 0;">Manage tenants who have left the property</p>
        </div>
        <a href="{{ route('admin.tenants.index') }}" style="padding: 12px 24px; background: white; color: #0071e3; border: 1px solid #d5d5d7; border-radius: 8px; text-decoration: none; font-weight: 500; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f5f5f7'" onmouseout="this.style.backgroundColor='white'">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
            </svg>
            Active Tenants
        </a>
    </div>
</div>

{{-- Search Box --}}
<div style="margin-bottom: 24px;">
    <form method="GET" action="{{ route('admin.tenants.archived.index') }}" style="display: flex; gap: 12px; max-width: 500px;">
        <div style="flex: 1; position: relative;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#86868b" stroke-width="2" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%);">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" name="search" value="{{ $search }}" placeholder="Search by name, email or phone..."
                   style="width: 100%; padding: 12px 16px 12px 44px; border: 1px solid #d5d5d7; border-radius: 8px; font-size: 14px; box-sizing: border-box;">
        </div>
        <button type="submit" style="padding: 12px 20px; background: #0071e3; color: white; border: none; border-radius: 8px; font-weight: 500; font-size: 14px; cursor: pointer;">Search</button>
        @if($search)
            <a href="{{ route('admin.tenants.archived.index') }}" style="padding: 12px 20px; background: #f5f5f7; color: #1d1d1f; border: 1px solid #d5d5d7; border-radius: 8px; font-weight: 500; font-size: 14px; text-decoration: none;">Clear</a>
        @endif
    </form>
</div>

@if (session('success'))
    <div style="background: #f0fdf4; border: 1px solid #22c55e; border-radius: 12px; padding: 16px; margin-bottom: 24px; color: #15803d;">
        <div style="display: flex; align-items: start; gap: 12px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0; margin-top: 2px;">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <p style="font-weight: 500; margin: 0;">{{ session('success') }}</p>
        </div>
    </div>
@endif

@if (session('error'))
    <div style="background: #fef2f2; border: 1px solid #f87171; border-radius: 12px; padding: 16px; margin-bottom: 24px; color: #991b1b;">
        <div style="display: flex; align-items: start; gap: 12px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0; margin-top: 2px;">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <p style="font-weight: 500; margin: 0;">{{ session('error') }}</p>
        </div>
    </div>
@endif

<div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.12); overflow: hidden;">
    <div style="padding: 24px; border-bottom: 1px solid #d5d5d7;">
        <h3 style="font-weight: 600; color: #1d1d1f; margin: 0; font-size: 18px; display: flex; align-items: center; gap: 10px;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#86868b" stroke-width="2">
                <path d="M21 8v13H3V8"></path>
                <path d="M1 3h22v5H1z"></path>
                <path d="M10 12h4"></path>
            </svg>
            Archived Tenants List
        </h3>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <thead>
                <tr style="background-color: #f5f5f7; border-bottom: 1px solid #d5d5d7;">
                    <th style="padding: 16px 24px; text-align: left; font-weight: 600; color: #86868b; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Tenant</th>
                    <th style="padding: 16px 24px; text-align: left; font-weight: 600; color: #86868b; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Room</th>
                    <th style="padding: 16px 24px; text-align: left; font-weight: 600; color: #86868b; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Stay Duration</th>
                    <th style="padding: 16px 24px; text-align: left; font-weight: 600; color: #86868b; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Leave Date</th>
                    <th style="padding: 16px 24px; text-align: left; font-weight: 600; color: #86868b; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Total Amount</th>
                    <th style="padding: 16px 24px; text-align: left; font-weight: 600; color: #86868b; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Reason</th>
                    <th style="padding: 16px 24px; text-align: center; font-weight: 600; color: #86868b; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tenants as $tenant)
                    <tr style="border-bottom: 1px solid #d5d5d7; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f5f5f7'" onmouseout="this.style.backgroundColor='white'">
                        <td style="padding: 16px 24px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                @if($tenant->photo_path)
                                    <img src="{{ asset('storage/' . $tenant->photo_path) }}" alt="{{ $tenant->name }}" 
                                         style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #d5d5d7;">
                                @else
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background-color: #f5f5f7; display: flex; align-items: center; justify-content: center; color: #86868b; font-size: 12px; font-weight: bold; border: 2px solid #d5d5d7;">
                                        {{ substr($tenant->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <div style="font-weight: 500; color: #1d1d1f;">{{ $tenant->name }}</div>
                                    <div style="font-size: 12px; color: #86868b;">{{ $tenant->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 16px 24px;">
                            <div style="font-weight: 500; color: #1d1d1f;">{{ $tenant->apartment->apartment_number ?? 'N/A' }}</div>
                            <div style="font-size: 12px; color: #86868b;">{{ $tenant->apartment->floor->name ?? 'N/A' }}</div>
                        </td>
                        <td style="padding: 16px 24px; color: #1d1d1f;">
                            <div style="font-weight: 500;">{{ $tenant->getStayDurationFormatted() }}</div>
                            <div style="font-size: 12px; color: #86868b;">{{ $tenant->move_in_date->format('M d, Y') }} - {{ $tenant->move_out_date->format('M d, Y') }}</div>
                        </td>
                        <td style="padding: 16px 24px; color: #86868b;">
                            {{ $tenant->archived_at->format('M d, Y') }}
                        </td>
                        <td style="padding: 16px 24px;">
                            <span style="font-weight: 600; color: #0071e3;">${{ number_format($tenant->getTotalFinalAmount(), 2) }}</span>
                        </td>
                        <td style="padding: 16px 24px; color: #86868b;">
                            {{ $tenant->leave_reason ?? 'Not specified' }}
                        </td>
                        <td style="padding: 16px 24px; text-align: center;">
                            <div style="display: flex; gap: 6px; justify-content: center; flex-wrap: wrap;">
                                {{-- View Invoice --}}
                                <a href="{{ route('admin.tenants.archived.invoice.view', $tenant) }}" target="_blank" style="padding: 6px 10px; background: #f0f0f5; color: #5856d6; border: 1px solid #d5d5d7; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 11px; transition: all 0.2s; display: inline-flex; align-items: center; gap: 4px;" title="View Invoice">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    View
                                </a>
                                
                                {{-- Download Invoice --}}
                                <a href="{{ route('admin.tenants.archived.invoice.download', $tenant) }}" style="padding: 6px 10px; background: #e8f5e9; color: #15803d; border: 1px solid #c8e6c9; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 11px; transition: all 0.2s; display: inline-flex; align-items: center; gap: 4px;" title="Download Invoice">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                    PDF
                                </a>
                                
                                {{-- Restore --}}
                                <form method="POST" action="{{ route('admin.tenants.archived.restore', $tenant) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to restore this tenant?');">
                                    @csrf
                                    <button type="submit" style="padding: 6px 10px; background: #fff3e0; color: #e65100; border: 1px solid #ffe0b2; border-radius: 6px; font-weight: 500; font-size: 11px; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 4px;" title="Restore Tenant">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"></path>
                                            <path d="M21 3v5h-5"></path>
                                            <path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"></path>
                                            <path d="M8 16H3v5"></path>
                                        </svg>
                                        Restore
                                    </button>
                                </form>
                                
                                {{-- Delete Permanently --}}
                                <form method="POST" action="{{ route('admin.tenants.archived.destroy', $tenant) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to permanently delete this tenant? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="padding: 6px 10px; background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; border-radius: 6px; font-weight: 500; font-size: 11px; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 4px;" title="Delete Permanently">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 48px 24px; text-align: center; color: #86868b;">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 16px; opacity: 0.5;">
                                <path d="M21 8v13H3V8"></path>
                                <path d="M1 3h22v5H1z"></path>
                                <path d="M10 12h4"></path>
                            </svg>
                            <p style="font-size: 16px; margin: 0 0 12px 0; font-weight: 500;">No archived tenants found</p>
                            <p style="font-size: 14px; margin: 0;">Tenants who have left will appear here</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="padding: 24px; border-top: 1px solid #d5d5d7; background: #f5f5f7;">
        {{ $tenants->links() }}
    </div>
</div>

@endsection
