@extends('layouts.admin')

@section('content')

<div style="background: linear-gradient(135deg, #f5f5f7 0%, #ffffff 100%); border-radius: 20px; padding: 32px; margin-bottom: 32px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 32px; font-weight: 600; color: #1d1d1f; margin: 0;">Fiscal Periods</h1>
            <p style="color: #86868b; margin: 8px 0 0 0;">Manage business opening & closing balances</p>
        </div>
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <a href="{{ route('admin.fiscal-periods.create') }}" style="padding: 12px 24px; background: #0071e3; color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="bi bi-plus-lg me-2"></i>New Fiscal Period
            </a>
            <a href="{{ route('admin.expenses.index') }}" style="padding: 12px 24px; background: #34c759; color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="bi bi-currency-dollar me-2"></i>Revenue & Expense
            </a>
            <a href="{{ route('admin.balance-sheet.index') }}" style="padding: 12px 24px; background: #5856d6; color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="bi bi-clipboard-data me-2"></i>Balance Sheet
            </a>
        </div>
    </div>
</div>

{{-- Current Period Card --}}
@if($currentPeriod)
<div style="background: linear-gradient(135deg, #007aff 0%, #0a84ff 100%); border-radius: 16px; padding: 24px; margin-bottom: 32px; color: white;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                <span style="padding: 4px 12px; background: rgba(255,255,255,0.2); border-radius: 20px; font-size: 12px; font-weight: 600;">CURRENT PERIOD</span>
            </div>
            <h2 style="font-size: 24px; font-weight: 700; margin: 0;">{{ $currentPeriod->name }}</h2>
            <p style="opacity: 0.9; margin: 8px 0 0 0;">
                {{ $currentPeriod->opening_date->format('M d, Y') }} - {{ $currentPeriod->closing_date->format('M d, Y') }}
            </p>
        </div>
        <div style="display: flex; gap: 24px; text-align: center;">
            <div>
                <p style="font-size: 14px; opacity: 0.8; margin: 0;">Opening Balance</p>
                <p style="font-size: 24px; font-weight: 700; margin: 4px 0 0 0;">${{ number_format($currentPeriod->opening_balance, 2) }}</p>
            </div>
            <div>
                <p style="font-size: 14px; opacity: 0.8; margin: 0;">Net Income</p>
                <p style="font-size: 24px; font-weight: 700; margin: 4px 0 0 0; color: {{ $currentPeriod->net_income >= 0 ? '#a8f0c6' : '#ffb3b3' }};">
                    {{ $currentPeriod->net_income >= 0 ? '+' : '' }}${{ number_format($currentPeriod->net_income, 2) }}
                </p>
            </div>
            <div>
                <p style="font-size: 14px; opacity: 0.8; margin: 0;">Running Balance</p>
                <p style="font-size: 24px; font-weight: 700; margin: 4px 0 0 0;">${{ number_format($currentPeriod->calculateClosingBalance(), 2) }}</p>
            </div>
        </div>
        <a href="{{ route('admin.fiscal-periods.show', $currentPeriod) }}" style="padding: 12px 24px; background: rgba(255,255,255,0.2); color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
            View Details
        </a>
    </div>
</div>
@else
<div style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 12px; padding: 24px; margin-bottom: 32px;">
    <div style="display: flex; align-items: center; gap: 16px;">
        <i class="bi bi-exclamation-triangle" style="font-size: 32px; color: #856404;"></i>
        <div>
            <h3 style="font-weight: 600; color: #856404; margin: 0;">No Active Fiscal Period</h3>
            <p style="color: #856404; margin: 8px 0 0 0;">Create a fiscal period to start tracking your opening and closing balances.</p>
        </div>
        <a href="{{ route('admin.fiscal-periods.create') }}" style="padding: 10px 20px; background: #856404; color: white; border-radius: 8px; text-decoration: none; font-weight: 600; margin-left: auto;">
            Create Now
        </a>
    </div>
</div>
@endif

@if (session('success'))
    <div style="background: #f0fdf4; border: 1px solid #22c55e; border-radius: 12px; padding: 16px; margin-bottom: 24px; color: #15803d;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <i class="bi bi-check-circle"></i>
            <p style="font-weight: 500; margin: 0;">{{ session('success') }}</p>
        </div>
    </div>
@endif

@if (session('error'))
    <div style="background: #fef2f2; border: 1px solid #ef4444; border-radius: 12px; padding: 16px; margin-bottom: 24px; color: #991b1b;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <i class="bi bi-x-circle"></i>
            <p style="font-weight: 500; margin: 0;">{{ session('error') }}</p>
        </div>
    </div>
@endif

{{-- Fiscal Periods List --}}
<div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; overflow: hidden;">
    <div style="padding: 20px; border-bottom: 1px solid #d5d5d7;">
        <h3 style="font-weight: 600; color: #1d1d1f; margin: 0;">All Fiscal Periods</h3>
    </div>

    @if($fiscalPeriods->count() > 0)
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f5f5f7;">
                    <th style="text-align: left; padding: 12px 16px; font-weight: 600; color: #86868b; font-size: 13px;">Period Name</th>
                    <th style="text-align: left; padding: 12px 16px; font-weight: 600; color: #86868b; font-size: 13px;">Opening Date</th>
                    <th style="text-align: left; padding: 12px 16px; font-weight: 600; color: #86868b; font-size: 13px;">Closing Date</th>
                    <th style="text-align: right; padding: 12px 16px; font-weight: 600; color: #86868b; font-size: 13px;">Opening Balance</th>
                    <th style="text-align: right; padding: 12px 16px; font-weight: 600; color: #86868b; font-size: 13px;">Closing Balance</th>
                    <th style="text-align: center; padding: 12px 16px; font-weight: 600; color: #86868b; font-size: 13px;">Status</th>
                    <th style="text-align: center; padding: 12px 16px; font-weight: 600; color: #86868b; font-size: 13px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($fiscalPeriods as $period)
                <tr style="border-bottom: 1px solid #f5f5f7;">
                    <td style="padding: 12px 16px; color: #1d1d1f;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            {{ $period->name }}
                            @if($period->is_current)
                                <span style="padding: 2px 8px; background: #007aff; color: white; border-radius: 10px; font-size: 11px; font-weight: 600;">CURRENT</span>
                            @endif
                        </div>
                    </td>
                    <td style="padding: 12px 16px; color: #1d1d1f;">{{ $period->opening_date->format('M d, Y') }}</td>
                    <td style="padding: 12px 16px; color: #1d1d1f;">{{ $period->closing_date->format('M d, Y') }}</td>
                    <td style="padding: 12px 16px; text-align: right; font-weight: 600; color: #1d1d1f;">${{ number_format($period->opening_balance, 2) }}</td>
                    <td style="padding: 12px 16px; text-align: right; font-weight: 600; color: {{ $period->closing_balance !== null ? '#34c759' : '#86868b' }};">
                        @if($period->closing_balance !== null)
                            ${{ number_format($period->closing_balance, 2) }}
                        @else
                            <span style="color: #86868b; font-style: italic;">Pending</span>
                        @endif
                    </td>
                    <td style="padding: 12px 16px; text-align: center;">
                        <span style="padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; background: {{ $period->status_color }}20; color: {{ $period->status_color }};">
                            {{ $period->status_label }}
                        </span>
                    </td>
                    <td style="padding: 12px 16px; text-align: center;">
                        <div style="display: flex; justify-content: center; gap: 8px;">
                            <a href="{{ route('admin.fiscal-periods.show', $period) }}" style="padding: 6px 12px; background: #f5f5f7; border-radius: 6px; color: #0071e3; text-decoration: none; font-size: 13px;" title="View">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($period->status !== 'closed')
                                <a href="{{ route('admin.fiscal-periods.edit', $period) }}" style="padding: 6px 12px; background: #f5f5f7; border-radius: 6px; color: #ff9500; text-decoration: none; font-size: 13px;" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if(!$period->is_current)
                                    <form method="POST" action="{{ route('admin.fiscal-periods.set-current', $period) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" style="padding: 6px 12px; background: #dcfce7; border: none; border-radius: 6px; color: #166534; cursor: pointer; font-size: 13px;" title="Set as Current">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    </form>
                                @endif
                            @endif
                            @if($period->status === 'closed')
                                <a href="{{ route('admin.fiscal-periods.export', $period) }}" style="padding: 6px 12px; background: #dbeafe; border-radius: 6px; color: #1e40af; text-decoration: none; font-size: 13px;" title="Export">
                                    <i class="bi bi-download"></i>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="padding: 48px; text-align: center; color: #86868b;">
        <i class="bi bi-calendar-x" style="font-size: 48px; display: block; margin-bottom: 16px;"></i>
        <p>No fiscal periods found.</p>
        <a href="{{ route('admin.fiscal-periods.create') }}" style="display: inline-block; margin-top: 16px; padding: 10px 24px; background: #0071e3; color: white; border-radius: 8px; text-decoration: none;">
            Create First Period
        </a>
    </div>
    @endif
</div>

@endsection
