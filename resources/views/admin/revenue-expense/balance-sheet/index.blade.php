@extends('layouts.admin')

@section('content')

<div style="background: linear-gradient(135deg, #f5f5f7 0%, #ffffff 100%); border-radius: 20px; padding: 32px; margin-bottom: 32px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 32px; font-weight: 600; color: #1d1d1f; margin: 0;">Balance Sheet</h1>
            <p style="color: #86868b; margin: 8px 0 0 0;">Manage Assets, Liabilities, and Equity</p>
        </div>
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <a href="{{ route('admin.balance-sheet.create') }}" style="padding: 12px 24px; background: #0071e3; color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="bi bi-plus-lg me-2"></i>Add Item
            </a>
            <a href="{{ route('admin.expenses.index') }}" style="padding: 12px 24px; background: #34c759; color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="bi bi-currency-dollar me-2"></i>Revenue & Expense
            </a>
            <a href="{{ route('admin.fiscal-periods.index') }}" style="padding: 12px 24px; background: #5856d6; color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="bi bi-calendar-range me-2"></i>Fiscal Periods
            </a>
        </div>
    </div>
</div>

{{-- Filter Section --}}
<div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 20px; margin-bottom: 24px;">
    <form method="GET" action="{{ route('admin.balance-sheet.index') }}" style="display: flex; gap: 16px; align-items: end; flex-wrap: wrap;">
        <div>
            <label style="display: block; color: #86868b; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Fiscal Period</label>
            <select name="fiscal_period_id" style="padding: 10px 16px; border: 1px solid #d5d5d7; border-radius: 8px; min-width: 200px;">
                <option value="">All Periods</option>
                @foreach($fiscalPeriods as $period)
                    <option value="{{ $period->id }}" {{ $fiscalPeriodId == $period->id ? 'selected' : '' }}>
                        {{ $period->name }} {{ $period->is_current ? '(Current)' : '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label style="display: block; color: #86868b; font-size: 13px; font-weight: 600; margin-bottom: 8px;">Type</label>
            <select name="item_type" style="padding: 10px 16px; border: 1px solid #d5d5d7; border-radius: 8px; min-width: 150px;">
                <option value="">All Types</option>
                <option value="asset" {{ $itemType == 'asset' ? 'selected' : '' }}>Assets</option>
                <option value="liability" {{ $itemType == 'liability' ? 'selected' : '' }}>Liabilities</option>
                <option value="equity" {{ $itemType == 'equity' ? 'selected' : '' }}>Equity</option>
            </select>
        </div>
        <button type="submit" style="padding: 10px 24px; background: #0071e3; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
            Filter
        </button>
    </form>
</div>

{{-- Summary Cards --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 32px;">
    {{-- Total Assets --}}
    <div style="background: linear-gradient(135deg, #34c759 0%, #30d158 100%); border-radius: 16px; padding: 24px; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="font-size: 14px; opacity: 0.9; margin: 0;">Total Assets</p>
                <h2 style="font-size: 28px; font-weight: 700; margin: 8px 0 0 0;">${{ number_format($totals['assets'], 2) }}</h2>
            </div>
            <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-building" style="font-size: 24px;"></i>
            </div>
        </div>
    </div>

    {{-- Total Liabilities --}}
    <div style="background: linear-gradient(135deg, #ff3b30 0%, #ff453a 100%); border-radius: 16px; padding: 24px; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="font-size: 14px; opacity: 0.9; margin: 0;">Total Liabilities</p>
                <h2 style="font-size: 28px; font-weight: 700; margin: 8px 0 0 0;">${{ number_format($totals['liabilities'], 2) }}</h2>
            </div>
            <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-credit-card" style="font-size: 24px;"></i>
            </div>
        </div>
    </div>

    {{-- Total Equity --}}
    <div style="background: linear-gradient(135deg, #007aff 0%, #0a84ff 100%); border-radius: 16px; padding: 24px; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="font-size: 14px; opacity: 0.9; margin: 0;">Total Equity</p>
                <h2 style="font-size: 28px; font-weight: 700; margin: 8px 0 0 0;">${{ number_format($totals['equity'], 2) }}</h2>
            </div>
            <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-pie-chart" style="font-size: 24px;"></i>
            </div>
        </div>
    </div>

    {{-- Net Worth --}}
    @php $netWorth = $totals['assets'] - $totals['liabilities']; @endphp
    <div style="background: linear-gradient(135deg, {{ $netWorth >= 0 ? '#5856d6' : '#ff9500' }} 0%, {{ $netWorth >= 0 ? '#5e5ce6' : '#ff9f0a' }} 100%); border-radius: 16px; padding: 24px; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="font-size: 14px; opacity: 0.9; margin: 0;">Net Worth</p>
                <h2 style="font-size: 28px; font-weight: 700; margin: 8px 0 0 0;">${{ number_format($netWorth, 2) }}</h2>
            </div>
            <div style="width: 48px; height: 48px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-cash-stack" style="font-size: 24px;"></i>
            </div>
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

{{-- Balance Sheet Items --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 24px;">
    {{-- Assets --}}
    <div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; overflow: hidden;">
        <div style="padding: 20px; border-bottom: 1px solid #d5d5d7; background: linear-gradient(135deg, #34c75920 0%, #34c75910 100%);">
            <h3 style="font-weight: 600; color: #166534; margin: 0;">
                <i class="bi bi-building me-2"></i>Assets
            </h3>
        </div>
        <div style="padding: 20px;">
            @php $assetItems = $items['asset'] ?? collect(); @endphp
            @if($assetItems->isNotEmpty())
                @foreach($assetItems as $subType => $subItems)
                    <div style="margin-bottom: 20px;">
                        <p style="color: #86868b; font-size: 13px; font-weight: 600; margin: 0 0 12px 0; text-transform: uppercase;">
                            {{ \App\Models\BalanceSheetItem::$subTypeLabels[$subType] ?? $subType }}
                        </p>
                        @foreach($subItems as $item)
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #f5f5f7; border-radius: 8px; margin-bottom: 8px;">
                                <div>
                                    <p style="font-weight: 600; color: #1d1d1f; margin: 0;">{{ $item->name }}</p>
                                    @if($item->description)
                                        <p style="font-size: 13px; color: #86868b; margin: 4px 0 0 0;">{{ $item->description }}</p>
                                    @endif
                                    <p style="font-size: 12px; color: #86868b; margin: 4px 0 0 0;">{{ $item->fiscalPeriod->name ?? 'N/A' }}</p>
                                </div>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <span style="font-weight: 700; color: #34c759; font-size: 16px;">${{ number_format($item->amount, 2) }}</span>
                                    @if($item->fiscalPeriod && $item->fiscalPeriod->status !== 'closed')
                                        <div style="display: flex; gap: 4px;">
                                            <a href="{{ route('admin.balance-sheet.edit', $item) }}" style="padding: 4px 8px; background: white; border-radius: 4px; color: #ff9500; text-decoration: none;">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.balance-sheet.destroy', $item) }}" style="display: inline;" onsubmit="return confirm('Delete this item?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="padding: 4px 8px; background: white; border: none; border-radius: 4px; color: #ff3b30; cursor: pointer;">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
                <div style="display: flex; justify-content: space-between; padding: 12px; border-top: 2px solid #34c759; margin-top: 8px;">
                    <span style="font-weight: 700; color: #1d1d1f;">Total Assets</span>
                    <span style="font-weight: 700; color: #34c759;">${{ number_format($totals['assets'], 2) }}</span>
                </div>
            @else
                <p style="color: #86868b; text-align: center; padding: 24px;">No assets recorded</p>
            @endif
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
            @php $liabilityItems = $items['liability'] ?? collect(); @endphp
            @if($liabilityItems->isNotEmpty())
                @foreach($liabilityItems as $subType => $subItems)
                    <div style="margin-bottom: 20px;">
                        <p style="color: #86868b; font-size: 13px; font-weight: 600; margin: 0 0 12px 0; text-transform: uppercase;">
                            {{ \App\Models\BalanceSheetItem::$subTypeLabels[$subType] ?? $subType }}
                        </p>
                        @foreach($subItems as $item)
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #f5f5f7; border-radius: 8px; margin-bottom: 8px;">
                                <div>
                                    <p style="font-weight: 600; color: #1d1d1f; margin: 0;">{{ $item->name }}</p>
                                    @if($item->description)
                                        <p style="font-size: 13px; color: #86868b; margin: 4px 0 0 0;">{{ $item->description }}</p>
                                    @endif
                                    <p style="font-size: 12px; color: #86868b; margin: 4px 0 0 0;">{{ $item->fiscalPeriod->name ?? 'N/A' }}</p>
                                </div>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <span style="font-weight: 700; color: #ff3b30; font-size: 16px;">${{ number_format($item->amount, 2) }}</span>
                                    @if($item->fiscalPeriod && $item->fiscalPeriod->status !== 'closed')
                                        <div style="display: flex; gap: 4px;">
                                            <a href="{{ route('admin.balance-sheet.edit', $item) }}" style="padding: 4px 8px; background: white; border-radius: 4px; color: #ff9500; text-decoration: none;">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form method="POST" action="{{ route('admin.balance-sheet.destroy', $item) }}" style="display: inline;" onsubmit="return confirm('Delete this item?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="padding: 4px 8px; background: white; border: none; border-radius: 4px; color: #ff3b30; cursor: pointer;">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
                <div style="display: flex; justify-content: space-between; padding: 12px; border-top: 2px solid #ff3b30; margin-top: 8px;">
                    <span style="font-weight: 700; color: #1d1d1f;">Total Liabilities</span>
                    <span style="font-weight: 700; color: #ff3b30;">${{ number_format($totals['liabilities'], 2) }}</span>
                </div>
            @else
                <p style="color: #86868b; text-align: center; padding: 24px;">No liabilities recorded</p>
            @endif
        </div>
    </div>

    {{-- Equity --}}
    <div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; overflow: hidden; grid-column: span 2;">
        <div style="padding: 20px; border-bottom: 1px solid #d5d5d7; background: linear-gradient(135deg, #007aff20 0%, #007aff10 100%);">
            <h3 style="font-weight: 600; color: #1e40af; margin: 0;">
                <i class="bi bi-pie-chart me-2"></i>Equity
            </h3>
        </div>
        <div style="padding: 20px;">
            @php $equityItems = $items['equity'] ?? collect(); @endphp
            @if($equityItems->isNotEmpty())
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                    @foreach($equityItems as $subType => $subItems)
                        <div>
                            <p style="color: #86868b; font-size: 13px; font-weight: 600; margin: 0 0 12px 0; text-transform: uppercase;">
                                {{ \App\Models\BalanceSheetItem::$subTypeLabels[$subType] ?? $subType }}
                            </p>
                            @foreach($subItems as $item)
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #f5f5f7; border-radius: 8px; margin-bottom: 8px;">
                                    <div>
                                        <p style="font-weight: 600; color: #1d1d1f; margin: 0;">{{ $item->name }}</p>
                                        <p style="font-size: 12px; color: #86868b; margin: 4px 0 0 0;">{{ $item->fiscalPeriod->name ?? 'N/A' }}</p>
                                    </div>
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <span style="font-weight: 700; color: #007aff; font-size: 16px;">${{ number_format($item->amount, 2) }}</span>
                                        @if($item->fiscalPeriod && $item->fiscalPeriod->status !== 'closed')
                                            <div style="display: flex; gap: 4px;">
                                                <a href="{{ route('admin.balance-sheet.edit', $item) }}" style="padding: 4px 8px; background: white; border-radius: 4px; color: #ff9500; text-decoration: none;">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form method="POST" action="{{ route('admin.balance-sheet.destroy', $item) }}" style="display: inline;" onsubmit="return confirm('Delete this item?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" style="padding: 4px 8px; background: white; border: none; border-radius: 4px; color: #ff3b30; cursor: pointer;">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                <div style="display: flex; justify-content: space-between; padding: 12px; border-top: 2px solid #007aff; margin-top: 16px;">
                    <span style="font-weight: 700; color: #1d1d1f;">Total Equity</span>
                    <span style="font-weight: 700; color: #007aff;">${{ number_format($totals['equity'], 2) }}</span>
                </div>
            @else
                <p style="color: #86868b; text-align: center; padding: 24px;">No equity recorded</p>
            @endif
        </div>
    </div>
</div>

@endsection
