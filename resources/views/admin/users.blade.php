@extends('layouts.admin')

@section('content')

<div style="background: linear-gradient(135deg, #f5f5f7 0%, #ffffff 100%); border-radius: 20px; padding: 32px; margin-bottom: 32px;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 32px; font-weight: 600; color: #1d1d1f; margin: 0;">User Management</h1>
            <p style="color: #86868b; margin: 8px 0 0 0;">Manage system users and their roles</p>
        </div>
        <div style="width: 56px; height: 56px; background: #f5f5f7; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #0071e3;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="9" cy="7" r="4"></circle>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
        </div>
    </div>
</div>

@if(session('success'))
    <div style="background: #f0fdf4; border: 1px solid #22c55e; border-radius: 12px; padding: 16px; margin-bottom: 24px; color: #15803d;">
        <div style="display: flex; align-items: start; gap: 12px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0; margin-top: 2px;">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <div>
                <p style="font-weight: 600; margin: 0 0 4px 0;">Success</p>
                <p style="margin: 0;">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

<div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 0; box-shadow: 0 1px 3px rgba(0,0,0,0.12); overflow: hidden;">
    <div style="padding: 24px; border-bottom: 1px solid #d5d5d7;">
        <h3 style="font-weight: 600; color: #1d1d1f; margin: 0; font-size: 18px;">Users</h3>
    </div>
    
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <thead>
                <tr style="background-color: #f5f5f7; border-bottom: 1px solid #d5d5d7;">
                    <th style="padding: 16px 24px; text-align: left; font-weight: 600; color: #86868b; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Name</th>
                    <th style="padding: 16px 24px; text-align: left; font-weight: 600; color: #86868b; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Email</th>
                    <th style="padding: 16px 24px; text-align: left; font-weight: 600; color: #86868b; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Role</th>
                    <th style="padding: 16px 24px; text-align: left; font-weight: 600; color: #86868b; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Status</th>
                    <th style="padding: 16px 24px; text-align: center; font-weight: 600; color: #86868b; text-transform: uppercase; font-size: 12px; letter-spacing: 0.5px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr style="border-bottom: 1px solid #d5d5d7; transition: background-color 0.2s;" onmouseover="this.style.backgroundColor='#f5f5f7'" onmouseout="this.style.backgroundColor='white'">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" style="display: contents;">
                        @csrf
                        @method('PUT')

                        <td style="padding: 16px 24px; color: #1d1d1f; font-weight: 500;">
                            {{ $user->name }}
                        </td>
                        <td style="padding: 16px 24px; color: #86868b;">
                            {{ $user->email }}
                        </td>
                        <td style="padding: 16px 24px;">
                            <select name="role" style="padding: 8px 12px; border: 1px solid #d5d5d7; border-radius: 8px; background: white; color: #1d1d1f; font-size: 14px; cursor: pointer; transition: border-color 0.2s;" onchange="this.form.submit()">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td style="padding: 16px 24px;">
                            <select name="status" style="padding: 8px 12px; border: 1px solid #d5d5d7; border-radius: 8px; background: white; color: #1d1d1f; font-size: 14px; cursor: pointer; transition: border-color 0.2s;" onchange="this.form.submit()">
                                <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="suspended" {{ $user->status === 'suspended' ? 'selected' : '' }}>
                                    Suspended
                                </option>
                            </select>
                        </td>
                        <td style="padding: 16px 24px; text-align: center;">
                            <button type="submit" style="padding: 8px 16px; background: #0071e3; color: white; border: none; border-radius: 8px; font-weight: 500; cursor: pointer; font-size: 14px; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#0077ed'" onmouseout="this.style.backgroundColor='#0071e3'">
                                Save
                            </button>
                        </td>
                    </form>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="padding: 24px; border-top: 1px solid #d5d5d7; background: #f5f5f7;">
        {{ $users->links() }}
    </div>
</div>

@endsection
