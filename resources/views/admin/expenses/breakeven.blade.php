@extends('layouts.admin')

@section('content')

<div style="background: linear-gradient(135deg, #f5f5f7 0%, #ffffff 100%); border-radius: 20px; padding: 32px; margin-bottom: 32px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 32px; font-weight: 600; color: #1d1d1f; margin: 0;">Break-Even Analysis</h1>
            <p style="color: #86868b; margin: 8px 0 0 0;">Financial analysis and profitability metrics for {{ $year }}</p>
        </div>
        <div style="display: flex; gap: 12px; align-items: center;">
            <form method="GET" style="display: flex; gap: 8px;">
                <select name="year" style="padding: 10px 16px; border: 1px solid #d5d5d7; border-radius: 8px;">
                    @for($y = now()->year - 2; $y <= now()->year + 1; $y++)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
                <button type="submit" style="padding: 10px 20px; background: #0071e3; color: white; border: none; border-radius: 8px; cursor: pointer;">
                    Update
                </button>
            </form>
            <a href="{{ route('admin.expenses.index') }}" style="padding: 12px 24px; background: #f5f5f7; color: #1d1d1f; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>
</div>

{{-- Break-Even Status Banner --}}
<div style="background: {{ $breakEvenData['reached_breakeven'] ? 'linear-gradient(135deg, #34c759 0%, #30d158 100%)' : 'linear-gradient(135deg, #ff3b30 0%, #ff453a 100%)' }}; border-radius: 16px; padding: 32px; margin-bottom: 32px; color: white;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 24px;">
        <div>
            <h2 style="font-size: 24px; font-weight: 700; margin: 0;">
                @if($breakEvenData['reached_breakeven'])
                    ✓ Break-Even Point Achieved!
                @else
                    ✗ Below Break-Even Point
                @endif
            </h2>
            <p style="margin: 8px 0 0 0; opacity: 0.9;">
                @if($breakEvenData['reached_breakeven'])
                    Your business is generating profit. Keep up the great work!
                @else
                    You need ${{ number_format($breakEvenData['break_even_point'] - $breakEvenData['total_income'], 2) }} more revenue to break even.
                @endif
            </p>
        </div>
        <div style="text-align: right;">
            <p style="font-size: 14px; opacity: 0.9; margin: 0;">Current Revenue</p>
            <p style="font-size: 32px; font-weight: 700; margin: 4px 0 0 0;">${{ number_format($breakEvenData['total_income'], 2) }}</p>
        </div>
    </div>
</div>

{{-- Key Metrics --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 32px;">
    {{-- Break-Even Point --}}
    <div style="background: white; border: 1px solid #d5d5d7; border-radius: 16px; padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
            <div style="width: 48px; height: 48px; background: #fff3cd; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-bullseye" style="font-size: 24px; color: #856404;"></i>
            </div>
        </div>
        <p style="color: #86868b; font-size: 14px; margin: 0;">Break-Even Point</p>
        <h3 style="font-size: 28px; font-weight: 700; color: #1d1d1f; margin: 8px 0 0 0;">${{ number_format($breakEvenData['break_even_point'], 2) }}</h3>
        <p style="color: #86868b; font-size: 13px; margin: 8px 0 0 0;">Revenue needed to cover all costs</p>
    </div>

    {{-- Contribution Margin --}}
    <div style="background: white; border: 1px solid #d5d5d7; border-radius: 16px; padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
            <div style="width: 48px; height: 48px; background: #d1ecf1; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-percent" style="font-size: 24px; color: #0c5460;"></i>
            </div>
        </div>
        <p style="color: #86868b; font-size: 14px; margin: 0;">Contribution Margin Ratio</p>
        <h3 style="font-size: 28px; font-weight: 700; color: #1d1d1f; margin: 8px 0 0 0;">{{ number_format($breakEvenData['contribution_margin_ratio'], 1) }}%</h3>
        <p style="color: #86868b; font-size: 13px; margin: 8px 0 0 0;">Revenue available to cover fixed costs</p>
    </div>

    {{-- Margin of Safety --}}
    <div style="background: white; border: 1px solid #d5d5d7; border-radius: 16px; padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
            <div style="width: 48px; height: 48px; background: {{ $breakEvenData['margin_of_safety'] >= 0 ? '#d4edda' : '#f8d7da' }}; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-shield-check" style="font-size: 24px; color: {{ $breakEvenData['margin_of_safety'] >= 0 ? '#155724' : '#721c24' }};"></i>
            </div>
        </div>
        <p style="color: #86868b; font-size: 14px; margin: 0;">Margin of Safety</p>
        <h3 style="font-size: 28px; font-weight: 700; color: {{ $breakEvenData['margin_of_safety'] >= 0 ? '#34c759' : '#ff3b30' }}; margin: 8px 0 0 0;">{{ number_format($breakEvenData['margin_of_safety'], 1) }}%</h3>
        <p style="color: #86868b; font-size: 13px; margin: 8px 0 0 0;">Buffer above break-even point</p>
    </div>

    {{-- Profit Margin --}}
    <div style="background: white; border: 1px solid #d5d5d7; border-radius: 16px; padding: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 16px;">
            <div style="width: 48px; height: 48px; background: {{ $breakEvenData['profit_margin'] >= 0 ? '#d4edda' : '#f8d7da' }}; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-graph-up-arrow" style="font-size: 24px; color: {{ $breakEvenData['profit_margin'] >= 0 ? '#155724' : '#721c24' }};"></i>
            </div>
        </div>
        <p style="color: #86868b; font-size: 14px; margin: 0;">Profit Margin</p>
        <h3 style="font-size: 28px; font-weight: 700; color: {{ $breakEvenData['profit_margin'] >= 0 ? '#34c759' : '#ff3b30' }}; margin: 8px 0 0 0;">{{ number_format($breakEvenData['profit_margin'], 1) }}%</h3>
        <p style="color: #86868b; font-size: 13px; margin: 8px 0 0 0;">Net profit as % of revenue</p>
    </div>
</div>

{{-- Financial Summary --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-bottom: 32px;">
    {{-- Income vs Expenses --}}
    <div style="background: white; border: 1px solid #d5d5d7; border-radius: 16px; padding: 24px;">
        <h3 style="font-weight: 600; color: #1d1d1f; margin: 0 0 20px 0;">Annual Summary</h3>
        
        <div style="margin-bottom: 16px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span style="color: #1d1d1f;">Total Income</span>
                <span style="font-weight: 600; color: #34c759;">${{ number_format($breakEvenData['total_income'], 2) }}</span>
            </div>
            <div style="height: 8px; background: #f5f5f7; border-radius: 4px; overflow: hidden;">
                <div style="height: 100%; background: #34c759; width: 100%;"></div>
            </div>
        </div>

        <div style="margin-bottom: 16px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span style="color: #1d1d1f;">Fixed Costs</span>
                <span style="font-weight: 600; color: #ff9500;">${{ number_format($breakEvenData['total_fixed_costs'], 2) }}</span>
            </div>
            <div style="height: 8px; background: #f5f5f7; border-radius: 4px; overflow: hidden;">
                @php $fixedPct = $breakEvenData['total_income'] > 0 ? min(($breakEvenData['total_fixed_costs'] / $breakEvenData['total_income']) * 100, 100) : 0; @endphp
                <div style="height: 100%; background: #ff9500; width: {{ $fixedPct }}%;"></div>
            </div>
        </div>

        <div style="margin-bottom: 16px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span style="color: #1d1d1f;">Variable Costs</span>
                <span style="font-weight: 600; color: #af52de;">${{ number_format($breakEvenData['total_variable_costs'], 2) }}</span>
            </div>
            <div style="height: 8px; background: #f5f5f7; border-radius: 4px; overflow: hidden;">
                @php $varPct = $breakEvenData['total_income'] > 0 ? min(($breakEvenData['total_variable_costs'] / $breakEvenData['total_income']) * 100, 100) : 0; @endphp
                <div style="height: 100%; background: #af52de; width: {{ $varPct }}%;"></div>
            </div>
        </div>

        <div style="margin-bottom: 16px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span style="color: #1d1d1f;">Bank/Financial Costs</span>
                <span style="font-weight: 600; color: #5856d6;">${{ number_format($breakEvenData['total_bank_costs'], 2) }}</span>
            </div>
            <div style="height: 8px; background: #f5f5f7; border-radius: 4px; overflow: hidden;">
                @php $bankPct = $breakEvenData['total_income'] > 0 ? min(($breakEvenData['total_bank_costs'] / $breakEvenData['total_income']) * 100, 100) : 0; @endphp
                <div style="height: 100%; background: #5856d6; width: {{ $bankPct }}%;"></div>
            </div>
        </div>

        <hr style="border: none; border-top: 1px solid #d5d5d7; margin: 20px 0;">

        <div style="display: flex; justify-content: space-between;">
            <span style="font-weight: 600; color: #1d1d1f;">Net {{ $breakEvenData['net_profit'] >= 0 ? 'Profit' : 'Loss' }}</span>
            <span style="font-weight: 700; font-size: 20px; color: {{ $breakEvenData['net_profit'] >= 0 ? '#34c759' : '#ff3b30' }};">
                {{ $breakEvenData['net_profit'] >= 0 ? '+' : '-' }}${{ number_format(abs($breakEvenData['net_profit']), 2) }}
            </span>
        </div>
    </div>

    {{-- Break-Even Formula --}}
    <div style="background: white; border: 1px solid #d5d5d7; border-radius: 16px; padding: 24px;">
        <h3 style="font-weight: 600; color: #1d1d1f; margin: 0 0 20px 0;">Break-Even Formula</h3>
        
        <div style="background: #f5f5f7; border-radius: 12px; padding: 20px; margin-bottom: 20px;">
            <p style="font-family: monospace; font-size: 14px; color: #1d1d1f; margin: 0; text-align: center;">
                Break-Even Point = Fixed Costs ÷ Contribution Margin Ratio
            </p>
        </div>

        <div style="margin-bottom: 16px;">
            <p style="color: #86868b; font-size: 13px; margin: 0 0 4px 0;">Fixed Costs (including Bank)</p>
            <p style="font-size: 18px; font-weight: 600; color: #1d1d1f; margin: 0;">
                ${{ number_format($breakEvenData['total_fixed_costs'] + $breakEvenData['total_bank_costs'], 2) }}
            </p>
        </div>

        <div style="margin-bottom: 16px;">
            <p style="color: #86868b; font-size: 13px; margin: 0 0 4px 0;">Contribution Margin Ratio</p>
            <p style="font-size: 18px; font-weight: 600; color: #1d1d1f; margin: 0;">
                {{ number_format($breakEvenData['contribution_margin_ratio'], 2) }}%
            </p>
            <p style="font-size: 12px; color: #86868b; margin: 4px 0 0 0;">
                (Revenue - Variable Costs) ÷ Revenue
            </p>
        </div>

        <hr style="border: none; border-top: 1px solid #d5d5d7; margin: 20px 0;">

        <div>
            <p style="color: #86868b; font-size: 13px; margin: 0 0 4px 0;">Break-Even Point</p>
            <p style="font-size: 24px; font-weight: 700; color: #0071e3; margin: 0;">
                ${{ number_format($breakEvenData['break_even_point'], 2) }}
            </p>
        </div>
    </div>
</div>

{{-- Monthly Trend Chart --}}
<div style="background: white; border: 1px solid #d5d5d7; border-radius: 16px; padding: 24px; margin-bottom: 32px;">
    <h3 style="font-weight: 600; color: #1d1d1f; margin: 0 0 20px 0;">Monthly Financial Trend - {{ $year }}</h3>
    <canvas id="monthlyChart" height="100"></canvas>
</div>

{{-- Monthly Data Table --}}
<div style="background: white; border: 1px solid #d5d5d7; border-radius: 16px; overflow: hidden;">
    <div style="padding: 20px; border-bottom: 1px solid #d5d5d7;">
        <h3 style="font-weight: 600; color: #1d1d1f; margin: 0;">Monthly Breakdown</h3>
    </div>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f5f5f7;">
                    <th style="text-align: left; padding: 12px 16px; font-weight: 600; color: #86868b; font-size: 13px;">Month</th>
                    <th style="text-align: right; padding: 12px 16px; font-weight: 600; color: #86868b; font-size: 13px;">Income</th>
                    <th style="text-align: right; padding: 12px 16px; font-weight: 600; color: #86868b; font-size: 13px;">Fixed Costs</th>
                    <th style="text-align: right; padding: 12px 16px; font-weight: 600; color: #86868b; font-size: 13px;">Variable Costs</th>
                    <th style="text-align: right; padding: 12px 16px; font-weight: 600; color: #86868b; font-size: 13px;">Bank Costs</th>
                    <th style="text-align: right; padding: 12px 16px; font-weight: 600; color: #86868b; font-size: 13px;">Total Costs</th>
                    <th style="text-align: right; padding: 12px 16px; font-weight: 600; color: #86868b; font-size: 13px;">Profit/Loss</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monthlyData as $data)
                <tr style="border-bottom: 1px solid #f5f5f7;">
                    <td style="padding: 12px 16px; font-weight: 500; color: #1d1d1f;">{{ $data['month'] }}</td>
                    <td style="padding: 12px 16px; text-align: right; color: #34c759;">${{ number_format($data['income'], 2) }}</td>
                    <td style="padding: 12px 16px; text-align: right; color: #ff9500;">${{ number_format($data['fixed_costs'], 2) }}</td>
                    <td style="padding: 12px 16px; text-align: right; color: #af52de;">${{ number_format($data['variable_costs'], 2) }}</td>
                    <td style="padding: 12px 16px; text-align: right; color: #5856d6;">${{ number_format($data['bank_costs'], 2) }}</td>
                    <td style="padding: 12px 16px; text-align: right; color: #1d1d1f;">${{ number_format($data['total_costs'], 2) }}</td>
                    <td style="padding: 12px 16px; text-align: right; font-weight: 600; color: {{ $data['profit'] >= 0 ? '#34c759' : '#ff3b30' }};">
                        {{ $data['profit'] >= 0 ? '+' : '' }}${{ number_format($data['profit'], 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    const ctx = document.getElementById('monthlyChart').getContext('2d');
    const monthlyData = @json($monthlyData);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: monthlyData.map(d => d.month),
            datasets: [
                {
                    label: 'Income',
                    data: monthlyData.map(d => d.income),
                    backgroundColor: 'rgba(52, 199, 89, 0.8)',
                    borderRadius: 4
                },
                {
                    label: 'Fixed Costs',
                    data: monthlyData.map(d => d.fixed_costs),
                    backgroundColor: 'rgba(255, 149, 0, 0.8)',
                    borderRadius: 4
                },
                {
                    label: 'Variable Costs',
                    data: monthlyData.map(d => d.variable_costs),
                    backgroundColor: 'rgba(175, 82, 222, 0.8)',
                    borderRadius: 4
                },
                {
                    label: 'Bank Costs',
                    data: monthlyData.map(d => d.bank_costs),
                    backgroundColor: 'rgba(88, 86, 214, 0.8)',
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>

@endsection
