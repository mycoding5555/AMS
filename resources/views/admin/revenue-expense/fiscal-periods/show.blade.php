@extends('layouts.admin')

@section('content')

<div style="background: linear-gradient(135deg, #f5f5f7 0%, #ffffff 100%); border-radius: 20px; padding: 32px; margin-bottom: 32px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                <span style="padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; background: {{ $fiscalPeriod->status_color }}20; color: {{ $fiscalPeriod->status_color }};">
                    {{ $fiscalPeriod->status_label }}
                </span>
                @if($fiscalPeriod->is_current)
                    <span style="padding: 4px 12px; background: #007aff; color: white; border-radius: 20px; font-size: 12px; font-weight: 600;">CURRENT</span>
                @endif
            </div>
            <h1 style="font-size: 32px; font-weight: 600; color: #1d1d1f; margin: 0;">{{ $fiscalPeriod->name }}</h1>
            <p style="color: #86868b; margin: 8px 0 0 0;">
                {{ $fiscalPeriod->opening_date->format('M d, Y') }} - {{ $fiscalPeriod->closing_date->format('M d, Y') }}
            </p>
        </div>
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            @if($fiscalPeriod->status !== 'closed')
                <a href="{{ route('admin.fiscal-periods.edit', $fiscalPeriod) }}" style="padding: 12px 24px; background: #ff9500; color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
                    <i class="bi bi-pencil me-2"></i>Edit
                </a>
                @if($fiscalPeriod->canBeClosed())
                    <form method="POST" action="{{ route('admin.fiscal-periods.close', $fiscalPeriod) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to close this fiscal period? This action cannot be undone.');">
                        @csrf
                        <button type="submit" style="padding: 12px 24px; background: #ff3b30; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                            <i class="bi bi-lock me-2"></i>Close Period
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('admin.fiscal-periods.export', $fiscalPeriod) }}" style="padding: 12px 24px; background: #34c759; color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
                    <i class="bi bi-file-earmark-text me-2"></i>View Report
                </a>
                <a href="{{ route('admin.fiscal-periods.download', $fiscalPeriod) }}" style="padding: 12px 24px; background: #5856d6; color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
                    <i class="bi bi-download me-2"></i>Download PDF
                </a>
            @endif
            <a href="{{ route('admin.fiscal-periods.index') }}" style="padding: 12px 24px; background: #f5f5f7; color: #1d1d1f; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
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

@if (session('error'))
    <div style="background: #fef2f2; border: 1px solid #ef4444; border-radius: 12px; padding: 16px; margin-bottom: 24px; color: #991b1b;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <i class="bi bi-x-circle"></i>
            <p style="font-weight: 500; margin: 0;">{{ session('error') }}</p>
        </div>
    </div>
@endif

{{-- Financial Summary Cards --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 32px;">
    {{-- Opening Balance --}}
    <div style="background: linear-gradient(135deg, #5856d6 0%, #5e5ce6 100%); border-radius: 16px; padding: 24px; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="font-size: 14px; opacity: 0.9; margin: 0;">Opening Balance</p>
                <h2 style="font-size: 28px; font-weight: 700; margin: 8px 0 0 0;">${{ number_format($fiscalPeriod->opening_balance, 2) }}</h2>
            </div>
            <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-wallet2" style="font-size: 24px;"></i>
            </div>
        </div>
    </div>

    {{-- Total Income --}}
    <div style="background: linear-gradient(135deg, #34c759 0%, #30d158 100%); border-radius: 16px; padding: 24px; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="font-size: 14px; opacity: 0.9; margin: 0;">Total Revenue</p>
                <h2 style="font-size: 28px; font-weight: 700; margin: 8px 0 0 0;">${{ number_format($totalIncome, 2) }}</h2>
            </div>
            <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-arrow-down-circle" style="font-size: 24px;"></i>
            </div>
        </div>
    </div>

    {{-- Total Expenses --}}
    <div style="background: linear-gradient(135deg, #ff9500 0%, #ff9f0a 100%); border-radius: 16px; padding: 24px; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="font-size: 14px; opacity: 0.9; margin: 0;">Total Expenses</p>
                <h2 style="font-size: 28px; font-weight: 700; margin: 8px 0 0 0;">${{ number_format($totalExpenses, 2) }}</h2>
            </div>
            <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-arrow-up-circle" style="font-size: 24px;"></i>
            </div>
        </div>
    </div>

    {{-- Net Income --}}
    <div style="background: linear-gradient(135deg, {{ $netIncome >= 0 ? '#007aff' : '#ff3b30' }} 0%, {{ $netIncome >= 0 ? '#0a84ff' : '#ff453a' }} 100%); border-radius: 16px; padding: 24px; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="font-size: 14px; opacity: 0.9; margin: 0;">Net {{ $netIncome >= 0 ? 'Profit' : 'Loss' }}</p>
                <h2 style="font-size: 28px; font-weight: 700; margin: 8px 0 0 0;">${{ number_format(abs($netIncome), 2) }}</h2>
            </div>
            <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-{{ $netIncome >= 0 ? 'graph-up-arrow' : 'graph-down-arrow' }}" style="font-size: 24px;"></i>
            </div>
        </div>
    </div>

    {{-- Closing Balance --}}
    <div style="background: linear-gradient(135deg, {{ $fiscalPeriod->status === 'closed' ? '#1d1d1f' : '#86868b' }} 0%, {{ $fiscalPeriod->status === 'closed' ? '#3d3d3f' : '#a0a0a2' }} 100%); border-radius: 16px; padding: 24px; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="font-size: 14px; opacity: 0.9; margin: 0;">
                    {{ $fiscalPeriod->status === 'closed' ? 'Closing Balance' : 'Projected Balance' }}
                </p>
                <h2 style="font-size: 28px; font-weight: 700; margin: 8px 0 0 0;">
                    ${{ number_format($fiscalPeriod->closing_balance ?? $fiscalPeriod->calculateClosingBalance(), 2) }}
                </h2>
            </div>
            <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-{{ $fiscalPeriod->status === 'closed' ? 'lock-fill' : 'unlock' }}" style="font-size: 24px;"></i>
            </div>
        </div>
    </div>
</div>

{{-- Balance Sheet Summary --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 32px;">
    {{-- Assets --}}
    <div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; overflow: hidden;">
        <div style="padding: 20px; border-bottom: 1px solid #d5d5d7; background: linear-gradient(135deg, #34c75920 0%, #34c75910 100%);">
            <h3 style="font-weight: 600; color: #166534; margin: 0;">
                <i class="bi bi-building me-2"></i>Assets
            </h3>
        </div>
        <div style="padding: 20px;">
            @php $assets = $balanceSheetItems['asset'] ?? collect(); @endphp
            @if($assets->isNotEmpty())
                @foreach($assets as $subType => $items)
                    <div style="margin-bottom: 16px;">
                        <p style="color: #86868b; font-size: 13px; font-weight: 600; margin: 0 0 8px 0; text-transform: uppercase;">
                            {{ \App\Models\BalanceSheetItem::$subTypeLabels[$subType] ?? $subType }}
                        </p>
                        @foreach($items as $item)
                            <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f5f5f7;">
                                <span style="color: #1d1d1f;">{{ $item->name }}</span>
                                <span style="font-weight: 600; color: #34c759;">${{ number_format($item->amount, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                @endforeach
                <div style="display: flex; justify-content: space-between; padding: 12px 0; border-top: 2px solid #34c759; margin-top: 8px;">
                    <span style="font-weight: 700; color: #1d1d1f;">Total Assets</span>
                    <span style="font-weight: 700; color: #34c759;">${{ number_format($fiscalPeriod->total_assets, 2) }}</span>
                </div>
            @else
                <p style="color: #86868b; text-align: center; padding: 24px;">No assets recorded</p>
            @endif
            <a href="{{ route('admin.balance-sheet.create', ['fiscal_period_id' => $fiscalPeriod->id]) }}" style="display: block; text-align: center; padding: 10px; margin-top: 12px; background: #f5f5f7; color: #34c759; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="bi bi-plus-lg me-1"></i>Add Asset
            </a>
        </div>
    </div>

    {{-- Liabilities --}}
    <div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; overflow: hidden;">
        <div style="padding: 20px; border-bottom: 1px solid #d5d5d7; background: linear-gradient(135deg, #ff3b3020 0%, #ff3b3010 100%);">
            <h3 style="font-weight: 600; color: #991b1b; margin: 0;">
                <i class="bi bi-credit-card me-2"></i>Liabilities
            </h3>
        </div>
        <div style="padding: 20px;">
            @php $liabilities = $balanceSheetItems['liability'] ?? collect(); @endphp
            @if($liabilities->isNotEmpty())
                @foreach($liabilities as $subType => $items)
                    <div style="margin-bottom: 16px;">
                        <p style="color: #86868b; font-size: 13px; font-weight: 600; margin: 0 0 8px 0; text-transform: uppercase;">
                            {{ \App\Models\BalanceSheetItem::$subTypeLabels[$subType] ?? $subType }}
                        </p>
                        @foreach($items as $item)
                            <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f5f5f7;">
                                <span style="color: #1d1d1f;">{{ $item->name }}</span>
                                <span style="font-weight: 600; color: #ff3b30;">${{ number_format($item->amount, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                @endforeach
                <div style="display: flex; justify-content: space-between; padding: 12px 0; border-top: 2px solid #ff3b30; margin-top: 8px;">
                    <span style="font-weight: 700; color: #1d1d1f;">Total Liabilities</span>
                    <span style="font-weight: 700; color: #ff3b30;">${{ number_format($fiscalPeriod->total_liabilities, 2) }}</span>
                </div>
            @else
                <p style="color: #86868b; text-align: center; padding: 24px;">No liabilities recorded</p>
            @endif
            <a href="{{ route('admin.balance-sheet.create', ['fiscal_period_id' => $fiscalPeriod->id]) }}" style="display: block; text-align: center; padding: 10px; margin-top: 12px; background: #f5f5f7; color: #ff3b30; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="bi bi-plus-lg me-1"></i>Add Liability
            </a>
        </div>
    </div>

    {{-- Equity --}}
    <div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; overflow: hidden;">
        <div style="padding: 20px; border-bottom: 1px solid #d5d5d7; background: linear-gradient(135deg, #007aff20 0%, #007aff10 100%);">
            <h3 style="font-weight: 600; color: #1e40af; margin: 0;">
                <i class="bi bi-pie-chart me-2"></i>Equity
            </h3>
        </div>
        <div style="padding: 20px;">
            @php $equity = $balanceSheetItems['equity'] ?? collect(); @endphp
            @if($equity->isNotEmpty())
                @foreach($equity as $subType => $items)
                    <div style="margin-bottom: 16px;">
                        <p style="color: #86868b; font-size: 13px; font-weight: 600; margin: 0 0 8px 0; text-transform: uppercase;">
                            {{ \App\Models\BalanceSheetItem::$subTypeLabels[$subType] ?? $subType }}
                        </p>
                        @foreach($items as $item)
                            <div style="display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f5f5f7;">
                                <span style="color: #1d1d1f;">{{ $item->name }}</span>
                                <span style="font-weight: 600; color: #007aff;">${{ number_format($item->amount, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                @endforeach
                <div style="display: flex; justify-content: space-between; padding: 12px 0; border-top: 2px solid #007aff; margin-top: 8px;">
                    <span style="font-weight: 700; color: #1d1d1f;">Total Equity</span>
                    <span style="font-weight: 700; color: #007aff;">${{ number_format($fiscalPeriod->total_equity, 2) }}</span>
                </div>
            @else
                <p style="color: #86868b; text-align: center; padding: 24px;">No equity recorded</p>
            @endif
            <a href="{{ route('admin.balance-sheet.create', ['fiscal_period_id' => $fiscalPeriod->id]) }}" style="display: block; text-align: center; padding: 10px; margin-top: 12px; background: #f5f5f7; color: #007aff; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="bi bi-plus-lg me-1"></i>Add Equity
            </a>
        </div>
    </div>
</div>

{{-- Accounting Equation --}}
<div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 24px; margin-bottom: 32px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h3 style="font-weight: 600; color: #1d1d1f; margin: 0;">Accounting Equation</h3>
        @if($accountingEquation['balanced'])
            <span style="padding: 4px 12px; background: #34c75920; color: #34c759; border-radius: 20px; font-size: 13px; font-weight: 600;">
                <i class="bi bi-check-circle me-1"></i>Balanced
            </span>
        @else
            <span style="padding: 4px 12px; background: #ff3b3020; color: #ff3b30; border-radius: 20px; font-size: 13px; font-weight: 600;">
                <i class="bi bi-exclamation-circle me-1"></i>Unbalanced
            </span>
        @endif
    </div>
    <div style="display: flex; align-items: center; justify-content: center; gap: 16px; flex-wrap: wrap; padding: 24px; background: #f5f5f7; border-radius: 12px;">
        <div style="text-align: center;">
            <p style="color: #86868b; font-size: 13px; margin: 0;">Assets</p>
            <p style="font-size: 28px; font-weight: 700; color: #34c759; margin: 4px 0 0 0;">${{ number_format($accountingEquation['assets'], 2) }}</p>
        </div>
        <span style="font-size: 24px; color: #86868b;">=</span>
        <div style="text-align: center;">
            <p style="color: #86868b; font-size: 13px; margin: 0;">Liabilities</p>
            <p style="font-size: 28px; font-weight: 700; color: #ff3b30; margin: 4px 0 0 0;">${{ number_format($accountingEquation['liabilities'], 2) }}</p>
        </div>
        <span style="font-size: 24px; color: #86868b;">+</span>
        <div style="text-align: center;">
            <p style="color: #86868b; font-size: 13px; margin: 0;">Equity</p>
            <p style="font-size: 28px; font-weight: 700; color: #007aff; margin: 4px 0 0 0;">${{ number_format($accountingEquation['equity'], 2) }}</p>
        </div>
    </div>
    @if(!$accountingEquation['balanced'])
        <div style="margin-top: 16px; padding: 12px; background: #fef3c7; border-radius: 8px; color: #92400e;">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Balance sheet is off by ${{ number_format($accountingEquation['difference'], 2) }}. Please review your entries.
        </div>
    @endif
</div>

{{-- Transactions List --}}
<div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; overflow: hidden; margin-bottom: 32px;">
    <div style="padding: 20px; border-bottom: 1px solid #d5d5d7; display: flex; justify-content: space-between; align-items: center;">
        <h3 style="font-weight: 600; color: #1d1d1f; margin: 0;">
            <i class="bi bi-receipt me-2"></i>Transactions ({{ $transactions->count() }})
        </h3>
        <a href="{{ route('admin.expenses.create') }}" style="padding: 8px 16px; background: #0071e3; color: white; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 14px;">
            <i class="bi bi-plus-lg me-1"></i>Add Transaction
        </a>
    </div>
    @if($transactions->count() > 0)
        <div style="max-height: 400px; overflow-y: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f5f5f7; position: sticky; top: 0;">
                    <tr>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #86868b; font-size: 13px;">Date</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #86868b; font-size: 13px;">Type</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #86868b; font-size: 13px;">Category</th>
                        <th style="padding: 12px 16px; text-align: left; font-weight: 600; color: #86868b; font-size: 13px;">Description</th>
                        <th style="padding: 12px 16px; text-align: right; font-weight: 600; color: #86868b; font-size: 13px;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                        <tr style="border-bottom: 1px solid #f5f5f7;">
                            <td style="padding: 12px 16px; color: #86868b; font-size: 14px;">{{ $transaction->transaction_date->format('M d, Y') }}</td>
                            <td style="padding: 12px 16px;">
                                <span style="padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; background: {{ $transaction->account_type === 'income' ? '#34c75920' : '#ff3b3020' }}; color: {{ $transaction->account_type === 'income' ? '#34c759' : '#ff3b30' }};">
                                    {{ ucfirst($transaction->account_type) }}
                                </span>
                            </td>
                            <td style="padding: 12px 16px; color: #1d1d1f; font-size: 14px;">{{ $transaction->category_label }}</td>
                            <td style="padding: 12px 16px; color: #86868b; font-size: 14px;">{{ $transaction->description ?: '-' }}</td>
                            <td style="padding: 12px 16px; text-align: right; font-weight: 600; color: {{ $transaction->account_type === 'income' ? '#34c759' : '#ff3b30' }}; font-size: 14px;">
                                {{ $transaction->account_type === 'income' ? '+' : '-' }}${{ number_format($transaction->amount, 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div style="padding: 48px; text-align: center;">
            <i class="bi bi-inbox" style="font-size: 48px; color: #d5d5d7;"></i>
            <p style="color: #86868b; margin: 16px 0 0 0;">No transactions recorded for this period</p>
        </div>
    @endif
</div>

{{-- Period Notes --}}
@if($fiscalPeriod->notes)
<div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 24px;">
    <h3 style="font-weight: 600; color: #1d1d1f; margin: 0 0 12px 0;">Notes</h3>
    <p style="color: #86868b; margin: 0; white-space: pre-wrap;">{{ $fiscalPeriod->notes }}</p>
</div>
@endif

@endsection
