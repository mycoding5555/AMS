@extends('layouts.admin')

@section('content')

<div style="background: linear-gradient(135deg, #f5f5f7 0%, #ffffff 100%); border-radius: 20px; padding: 32px; margin-bottom: 32px;">
    <div style="display: flex; justify-content: space-between; align-items: start;">
        <div style="flex: 1;">
            @if($selectedRoomId)
                @php
                    $room = \App\Models\Apartment::find($selectedRoomId);
                @endphp
                <p style="color: #86868b; font-size: 14px; margin: 0 0 8px 0;">
                    <a href="{{ route('admin.dashboard') }}" style="color: #0071e3; text-decoration: none;">Dashboard</a>
                    <span style="color: #d5d5d7; margin: 0 8px;">/</span>
                    Room {{ $room->room_number }} (Apt #{{ $room->apartment_number }})
                </p>
                <h1 style="font-size: 32px; font-weight: 600; color: #1d1d1f; margin: 0;">Tenant Information</h1>
                <p style="color: #86868b; margin: 8px 0 0 0;">Manage tenants for room {{ $room->room_number }}</p>
            @else
                <h1 style="font-size: 32px; font-weight: 600; color: #1d1d1f; margin: 0;">All Tenants</h1>
                <p style="color: #86868b; margin: 8px 0 0 0;">View and manage all active tenants</p>
            @endif
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('admin.tenants.archived.index') }}" style="padding: 12px 24px; background: white; color: #86868b; border: 1px solid #d5d5d7; border-radius: 8px; text-decoration: none; font-weight: 500; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f5f5f7'" onmouseout="this.style.backgroundColor='white'">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 8v13H3V8"></path>
                    <path d="M1 3h22v5H1z"></path>
                    <path d="M10 12h4"></path>
                </svg>
                Archived
            </a>
            <a href="{{ route('admin.tenants.create') }}" style="padding: 12px 24px; background: #0071e3; color: white; border: none; border-radius: 8px; text-decoration: none; font-weight: 500; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#0077ed'" onmouseout="this.style.backgroundColor='#0071e3'">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                Add Tenant
            </a>
        </div>
    </div>
</div>

@if($selectedRoomId)
    <div style="margin-bottom: 24px;">
        <a href="{{ route('admin.dashboard') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; background: white; color: #0071e3; border: 1px solid #d5d5d7; border-radius: 8px; text-decoration: none; font-weight: 500; font-size: 14px; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f5f5f7'" onmouseout="this.style.backgroundColor='white'">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Back to Dashboard
        </a>
    </div>
@endif

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

<div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.12); overflow: hidden;">
    <div style="padding: 24px; border-bottom: 1px solid #d5d5d7;">
        <h3 style="font-weight: 600; color: #1d1d1f; margin: 0; font-size: 18px;">
            @if($selectedRoomId)
                Room {{ $room->room_number }} Tenants
            @else
                Tenants List
            @endif
        </h3>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <thead>
                <tr style="background-color: #f5f5f7; border-bottom: 1px solid #d5d5d7;">
                    <th style="padding: 16px 24px; text-align: left; font-weight: 600; color: #86868b; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Photo</th>
                    <th style="padding: 16px 24px; text-align: left; font-weight: 600; color: #86868b; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Name</th>
                    <th style="padding: 16px 24px; text-align: left; font-weight: 600; color: #86868b; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Email</th>
                    <th style="padding: 16px 24px; text-align: left; font-weight: 600; color: #86868b; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Phone</th>
                    <th style="padding: 16px 24px; text-align: left; font-weight: 600; color: #86868b; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Room</th>
                    <th style="padding: 16px 24px; text-align: left; font-weight: 600; color: #86868b; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Move-In</th>
                    <th style="padding: 16px 24px; text-align: left; font-weight: 600; color: #86868b; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Status</th>
                    <th style="padding: 16px 24px; text-align: center; font-weight: 600; color: #86868b; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tenants as $tenant)
                    <tr style="border-bottom: 1px solid #d5d5d7; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f5f5f7'" onmouseout="this.style.backgroundColor='white'">
                        <td style="padding: 16px 24px;">
                            @if($tenant->photo_path)
                                <img src="{{ asset('storage/' . $tenant->photo_path) }}" alt="{{ $tenant->name }}" 
                                     style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 2px solid #d5d5d7;">
                            @else
                                <div style="width: 44px; height: 44px; border-radius: 50%; background-color: #f5f5f7; display: flex; align-items: center; justify-content: center; color: #86868b; font-size: 12px; font-weight: bold; border: 2px solid #d5d5d7;">
                                    {{ substr($tenant->name, 0, 1) }}
                                </div>
                            @endif
                        </td>
                        <td style="padding: 16px 24px; color: #1d1d1f; font-weight: 500;">
                            {{ $tenant->name }}
                        </td>
                        <td style="padding: 16px 24px; color: #86868b;">
                            {{ $tenant->email }}
                        </td>
                        <td style="padding: 16px 24px; color: #86868b;">
                            {{ $tenant->phone }}
                        </td>
                        <td style="padding: 16px 24px; color: #1d1d1f;">
                            <div style="font-weight: 500;">{{ $tenant->apartment->apartment_number ?? 'N/A' }}</div>
                            <div style="color: #86868b; font-size: 13px; margin-top: 2px;">{{ $tenant->apartment->floor->name ?? 'N/A' }}</div>
                        </td>
                        <td style="padding: 16px 24px; color: #86868b;">
                            {{ $tenant->move_in_date->format('M d, Y') }}
                        </td>
                        <td style="padding: 16px 24px;">
                            @php
                                $statusColors = [
                                    'active' => ['bg' => '#f0fdf4', 'text' => '#15803d', 'label' => 'Active'],
                                    'inactive' => ['bg' => '#fef3c7', 'text' => '#92400e', 'label' => 'Inactive'],
                                    'moved-out' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'label' => 'Moved Out']
                                ];
                                $status = $statusColors[$tenant->status] ?? ['bg' => '#f5f5f7', 'text' => '#86868b', 'label' => ucfirst($tenant->status)];
                            @endphp
                            <span style="background: {{ $status['bg'] }}; color: {{ $status['text'] }}; padding: 6px 12px; border-radius: 6px; font-weight: 500; font-size: 12px;">
                                {{ $status['label'] }}
                            </span>
                        </td>
                        <td style="padding: 16px 24px; text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                                @if($tenant->status === 'active')
                                <a href="{{ route('admin.tenants.leave', $tenant) }}" style="padding: 8px 12px; background: #fff3e0; color: #e65100; border: 1px solid #ffe0b2; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 12px; transition: all 0.2s; display: inline-flex; align-items: center; gap: 4px;" onmouseover="this.style.backgroundColor='#ffe0b2'" onmouseout="this.style.backgroundColor='#fff3e0'" title="Process tenant leave">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                        <polyline points="16 17 21 12 16 7"></polyline>
                                        <line x1="21" y1="12" x2="9" y2="12"></line>
                                    </svg>
                                    Leave
                                </a>
                                @endif
                                <a href="{{ route('admin.tenants.edit', $tenant) }}" style="padding: 8px 12px; background: #f5f5f7; color: #0071e3; border: 1px solid #d5d5d7; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 12px; transition: all 0.2s; display: inline-flex; align-items: center; gap: 4px;" onmouseover="this.style.backgroundColor='#e8e8ea'" onmouseout="this.style.backgroundColor='#f5f5f7'">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                    </svg>
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.tenants.destroy', $tenant) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this tenant?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="padding: 8px 12px; background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; border-radius: 6px; font-weight: 500; font-size: 12px; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 4px;" onmouseover="this.style.backgroundColor='#fca5a5'" onmouseout="this.style.backgroundColor='#fee2e2'">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
                        <td colspan="8" style="padding: 48px 24px; text-align: center; color: #86868b;">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin: 0 auto 16px; opacity: 0.5;">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                            </svg>
                            <p style="font-size: 16px; margin: 0 0 12px 0; font-weight: 500;">No tenants found</p>
                            <a href="{{ route('admin.tenants.create') }}" style="color: #0071e3; text-decoration: none; font-weight: 500;">Add your first tenant</a>
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
