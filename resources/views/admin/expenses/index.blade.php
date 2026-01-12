@extends('layouts.admin')

@section('content')

<div style="background: linear-gradient(135deg, #f5f5f7 0%, #ffffff 100%); border-radius: 20px; padding: 32px; margin-bottom: 32px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 32px; font-weight: 600; color: #1d1d1f; margin: 0;">Accounts & Expenses</h1>
            <p style="color: #86868b; margin: 8px 0 0 0;">Manage income, expenses, and financial tracking</p>
        </div>
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <a href="{{ route('admin.expenses.create') }}" style="padding: 12px 24px; background: #0071e3; color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="bi bi-plus-lg me-2"></i>Add Entry
            </a>
            <a href="{{ route('admin.expenses.breakeven') }}" style="padding: 12px 24px; background: #34c759; color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="bi bi-graph-up me-2"></i>Break-Even Analysis
            </a>
        </div>
    </div>
</div>

{{-- Sync Payments Alert --}}
@if($unsyncedPayments > 0)
<div style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <i class="bi bi-info-circle" style="font-size: 24px; color: #856404;"></i>
            <div>
                <p style="font-weight: 600; color: #856404; margin: 0;">{{ $unsyncedPayments }} tenant payment(s) ready to sync</p>
                <p style="color: #856404; margin: 4px 0 0 0; font-size: 14px;">Import tenant payments as income entries for accurate tracking.</p>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.expenses.sync-payments') }}">
            @csrf
            <input type="hidden" name="month" value="{{ $month }}">
            <input type="hidden" name="year" value="{{ $year }}">
            <button type="submit" style="padding: 10px 20px; background: #856404; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="bi bi-arrow-repeat me-2"></i>Sync Now
            </button>
        </form>
    </div>
</div>
@endif

{{-- Filter Section --}}
<div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
    <form method="GET" action="{{ route('admin.expenses.index') }}" style="display: flex; gap: 16px; align-items: end; flex-wrap: wrap;">
        <div>
            <label style="display: block; color: #86868b; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Month</label>
            <select name="month" style="padding: 10px 16px; border: 1px solid #d5d5d7; border-radius: 8px; min-width: 120px;">
                @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endfor
            </select>
        </div>
        <div>
            <label style="display: block; color: #86868b; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Year</label>
            <select name="year" style="padding: 10px 16px; border: 1px solid #d5d5d7; border-radius: 8px; min-width: 100px;">
                @for($y = now()->year - 2; $y <= now()->year + 1; $y++)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        <div>
            <label style="display: block; color: #86868b; font-size: 13px; font-weight: 600; margin-bottom: 8px;">View</label>
            <select name="view" style="padding: 10px 16px; border: 1px solid #d5d5d7; border-radius: 8px; min-width: 120px;">
                <option value="all" {{ $view == 'all' ? 'selected' : '' }}>All Entries</option>
                <option value="income" {{ $view == 'income' ? 'selected' : '' }}>Income Only</option>
                <option value="expense" {{ $view == 'expense' ? 'selected' : '' }}>Expenses Only</option>
            </select>
        </div>
        <button type="submit" style="padding: 10px 24px; background: #0071e3; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
            Filter
        </button>
    </form>
</div>

{{-- Summary Cards --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 32px;">
    {{-- Total Income --}}
    <div style="background: linear-gradient(135deg, #34c759 0%, #30d158 100%); border-radius: 16px; padding: 24px; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="font-size: 14px; opacity: 0.9; margin: 0;">Total Income</p>
                <h2 style="font-size: 28px; font-weight: 700; margin: 8px 0 0 0;">${{ number_format($totalIncome, 2) }}</h2>
            </div>
            <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-arrow-down-circle" style="font-size: 24px;"></i>
            </div>
        </div>
    </div>

    {{-- Fixed Costs --}}
    <div style="background: linear-gradient(135deg, #ff9500 0%, #ff9f0a 100%); border-radius: 16px; padding: 24px; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="font-size: 14px; opacity: 0.9; margin: 0;">Fixed Costs</p>
                <h2 style="font-size: 28px; font-weight: 700; margin: 8px 0 0 0;">${{ number_format($totalFixedCosts, 2) }}</h2>
            </div>
            <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-lock" style="font-size: 24px;"></i>
            </div>
        </div>
    </div>

    {{-- Variable Costs --}}
    <div style="background: linear-gradient(135deg, #af52de 0%, #bf5af2 100%); border-radius: 16px; padding: 24px; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="font-size: 14px; opacity: 0.9; margin: 0;">Variable Costs</p>
                <h2 style="font-size: 28px; font-weight: 700; margin: 8px 0 0 0;">${{ number_format($totalVariableCosts, 2) }}</h2>
            </div>
            <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-graph-down" style="font-size: 24px;"></i>
            </div>
        </div>
    </div>

    {{-- Bank Costs --}}
    <div style="background: linear-gradient(135deg, #5856d6 0%, #5e5ce6 100%); border-radius: 16px; padding: 24px; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="font-size: 14px; opacity: 0.9; margin: 0;">Bank/Financial</p>
                <h2 style="font-size: 28px; font-weight: 700; margin: 8px 0 0 0;">${{ number_format($totalBankCosts, 2) }}</h2>
            </div>
            <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-bank" style="font-size: 24px;"></i>
            </div>
        </div>
    </div>

    {{-- Net Profit/Loss --}}
    <div style="background: linear-gradient(135deg, {{ $netProfit >= 0 ? '#007aff' : '#ff3b30' }} 0%, {{ $netProfit >= 0 ? '#0a84ff' : '#ff453a' }} 100%); border-radius: 16px; padding: 24px; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="font-size: 14px; opacity: 0.9; margin: 0;">Net {{ $netProfit >= 0 ? 'Profit' : 'Loss' }}</p>
                <h2 style="font-size: 28px; font-weight: 700; margin: 8px 0 0 0;">${{ number_format(abs($netProfit), 2) }}</h2>
            </div>
            <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-{{ $netProfit >= 0 ? 'graph-up-arrow' : 'graph-down-arrow' }}" style="font-size: 24px;"></i>
            </div>
        </div>
    </div>
</div>

{{-- Break-Even Quick Stats --}}
<div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 24px; margin-bottom: 32px;">
    <h3 style="font-weight: 600; color: #1d1d1f; margin: 0 0 16px 0;">Break-Even Status ({{ $year }})</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px;">
        <div style="text-align: center; padding: 16px; background: #f5f5f7; border-radius: 12px;">
            <p style="color: #86868b; font-size: 13px; margin: 0;">Break-Even Point</p>
            <p style="font-size: 20px; font-weight: 700; color: #1d1d1f; margin: 8px 0 0 0;">${{ number_format($breakEvenData['break_even_point'], 2) }}</p>
        </div>
        <div style="text-align: center; padding: 16px; background: #f5f5f7; border-radius: 12px;">
            <p style="color: #86868b; font-size: 13px; margin: 0;">Margin of Safety</p>
            <p style="font-size: 20px; font-weight: 700; color: {{ $breakEvenData['margin_of_safety'] >= 0 ? '#34c759' : '#ff3b30' }}; margin: 8px 0 0 0;">{{ number_format($breakEvenData['margin_of_safety'], 1) }}%</p>
        </div>
        <div style="text-align: center; padding: 16px; background: #f5f5f7; border-radius: 12px;">
            <p style="color: #86868b; font-size: 13px; margin: 0;">Contribution Margin</p>
            <p style="font-size: 20px; font-weight: 700; color: #1d1d1f; margin: 8px 0 0 0;">{{ number_format($breakEvenData['contribution_margin_ratio'], 1) }}%</p>
        </div>
        <div style="text-align: center; padding: 16px; background: {{ $breakEvenData['reached_breakeven'] ? '#f0fdf4' : '#fef2f2' }}; border-radius: 12px;">
            <p style="color: #86868b; font-size: 13px; margin: 0;">Status</p>
            <p style="font-size: 20px; font-weight: 700; color: {{ $breakEvenData['reached_breakeven'] ? '#34c759' : '#ff3b30' }}; margin: 8px 0 0 0;">
                {{ $breakEvenData['reached_breakeven'] ? '✓ Break-Even Reached' : '✗ Below Break-Even' }}
            </p>
        </div>
    </div>
</div>

@if (session('success'))
    <div style="background: #f0fdf4; border: 1px solid #22c55e; border-radius: 12px; padding: 16px; margin-bottom: 24px; color: #15803d;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <i class="bi bi-check-circle"></i>
            <p style="font-weight: 500; margin: 0;">{{ session('success') }}</p>
        </div>
    </div>
@endif

{{-- Accounts Table --}}
<div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; overflow: hidden;">
    <div style="padding: 20px; border-bottom: 1px solid #d5d5d7;">
        <h3 style="font-weight: 600; color: #1d1d1f; margin: 0;">Transaction History</h3>
    </div>

    @if($accounts->count() > 0)
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f5f5f7;">
                    <th style="text-align: left; padding: 12px 16px; font-weight: 600; color: #86868b; font-size: 13px;">Date</th>
                    <th style="text-align: left; padding: 12px 16px; font-weight: 600; color: #86868b; font-size: 13px;">Type</th>
                    <th style="text-align: left; padding: 12px 16px; font-weight: 600; color: #86868b; font-size: 13px;">Category</th>
                    <th style="text-align: left; padding: 12px 16px; font-weight: 600; color: #86868b; font-size: 13px;">Description</th>
                    <th style="text-align: left; padding: 12px 16px; font-weight: 600; color: #86868b; font-size: 13px;">Cost Type</th>
                    <th style="text-align: right; padding: 12px 16px; font-weight: 600; color: #86868b; font-size: 13px;">Amount</th>
                    <th style="text-align: center; padding: 12px 16px; font-weight: 600; color: #86868b; font-size: 13px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($accounts as $account)
                <tr style="border-bottom: 1px solid #f5f5f7;">
                    <td style="padding: 12px 16px; color: #1d1d1f;">{{ $account->transaction_date->format('M d, Y') }}</td>
                    <td style="padding: 12px 16px;">
                        <span style="padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; background: {{ $account->account_type === 'income' ? '#dcfce7' : '#fee2e2' }}; color: {{ $account->account_type === 'income' ? '#166534' : '#991b1b' }};">
                            {{ ucfirst($account->account_type) }}
                        </span>
                    </td>
                    <td style="padding: 12px 16px; color: #1d1d1f;">{{ $account->category_label }}</td>
                    <td style="padding: 12px 16px; color: #86868b;">{{ $account->description ?? '-' }}</td>
                    <td style="padding: 12px 16px;">
                        <span style="padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;
                            @if($account->cost_type === 'fixed') background: #fef3c7; color: #92400e;
                            @elseif($account->cost_type === 'variable') background: #f3e8ff; color: #6b21a8;
                            @elseif($account->cost_type === 'bank') background: #dbeafe; color: #1e40af;
                            @else background: #dcfce7; color: #166534;
                            @endif">
                            {{ $account->cost_type_label }}
                        </span>
                    </td>
                    <td style="padding: 12px 16px; text-align: right; font-weight: 600; color: {{ $account->account_type === 'income' ? '#34c759' : '#ff3b30' }};">
                        {{ $account->account_type === 'income' ? '+' : '-' }}${{ number_format($account->amount, 2) }}
                    </td>
                    <td style="padding: 12px 16px; text-align: center;">
                        <div style="display: flex; justify-content: center; gap: 8px;">
                            <a href="{{ route('admin.expenses.edit', $account) }}" style="padding: 6px 12px; background: #f5f5f7; border-radius: 6px; color: #0071e3; text-decoration: none; font-size: 13px;">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.expenses.destroy', $account) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this entry?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="padding: 6px 12px; background: #fee2e2; border: none; border-radius: 6px; color: #991b1b; cursor: pointer; font-size: 13px;">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="padding: 48px; text-align: center; color: #86868b;">
        <i class="bi bi-journal-x" style="font-size: 48px; display: block; margin-bottom: 16px;"></i>
        <p>No transactions found for this period.</p>
        <a href="{{ route('admin.expenses.create') }}" style="display: inline-block; margin-top: 16px; padding: 10px 24px; background: #0071e3; color: white; border-radius: 8px; text-decoration: none;">
            Add First Entry
        </a>
    </div>
    @endif
</div>

@endsection
