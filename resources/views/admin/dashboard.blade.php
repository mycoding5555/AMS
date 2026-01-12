@extends('layouts.admin')

@section('content')

<div style="background: linear-gradient(135deg, #f5f5f7 0%, #ffffff 100%); border-radius: 20px; padding: 32px; margin-bottom: 32px;">
    <h1 style="font-size: 32px; font-weight: 600; color: #1d1d1f; margin: 0;">Dashboard Overview</h1>
    <p style="color: #86868b; margin: 8px 0 0 0;">Manage your property with ease</p>
</div>

{{-- Financial Summary Cards --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
    {{-- Yearly Income --}}
    <div style="background: linear-gradient(135deg, #34c759 0%, #30d158 100%); border-radius: 16px; padding: 20px; color: white;">
        <p style="font-size: 13px; opacity: 0.9; margin: 0;">Yearly Income</p>
        <h2 style="font-size: 24px; font-weight: 700; margin: 8px 0 0 0;">${{ number_format($yearlyIncome, 2) }}</h2>
    </div>
    
    {{-- Yearly Expenses --}}
    <div style="background: linear-gradient(135deg, #ff3b30 0%, #ff453a 100%); border-radius: 16px; padding: 20px; color: white;">
        <p style="font-size: 13px; opacity: 0.9; margin: 0;">Yearly Expenses</p>
        <h2 style="font-size: 24px; font-weight: 700; margin: 8px 0 0 0;">${{ number_format($yearlyExpenses, 2) }}</h2>
    </div>
    
    {{-- Net Profit/Loss --}}
    <div style="background: linear-gradient(135deg, {{ $yearlyProfit >= 0 ? '#007aff' : '#ff9500' }} 0%, {{ $yearlyProfit >= 0 ? '#0a84ff' : '#ff9f0a' }} 100%); border-radius: 16px; padding: 20px; color: white;">
        <p style="font-size: 13px; opacity: 0.9; margin: 0;">Net {{ $yearlyProfit >= 0 ? 'Profit' : 'Loss' }}</p>
        <h2 style="font-size: 24px; font-weight: 700; margin: 8px 0 0 0;">{{ $yearlyProfit >= 0 ? '+' : '-' }}${{ number_format(abs($yearlyProfit), 2) }}</h2>
    </div>
    
    {{-- Break-Even Status --}}
    <div style="background: linear-gradient(135deg, {{ $reachedBreakeven ? '#5856d6' : '#ff9500' }} 0%, {{ $reachedBreakeven ? '#5e5ce6' : '#ff9f0a' }} 100%); border-radius: 16px; padding: 20px; color: white;">
        <p style="font-size: 13px; opacity: 0.9; margin: 0;">Break-Even</p>
        <h2 style="font-size: 18px; font-weight: 700; margin: 8px 0 0 0;">{{ $reachedBreakeven ? '✓ Achieved' : '$'.number_format($breakEvenPoint - $yearlyIncome, 0).' needed' }}</h2>
    </div>
</div>

{{-- KPI Cards --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 32px;">
  

    <div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.12);">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="color: #86868b; font-size: 14px; font-weight: 500; margin: 0 0 12px 0;">Total Users</p>
                <h2 style="color: #1d1d1f; font-size: 32px; font-weight: 600; margin: 0;">{{ $totalUsers }}</h2>
            </div>
            <div style="width: 56px; height: 56px; background: #f5f5f7; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #0071e3;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
        </div>
    </div>

    <div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.12);">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="color: #86868b; font-size: 14px; font-weight: 500; margin: 0 0 12px 0;">Total Rooms</p>
                <h2 style="color: #1d1d1f; font-size: 32px; font-weight: 600; margin: 0;">{{ $totalApartments }}</h2>
                <p style="color: #86868b; font-size: 12px; margin: 8px 0 0 0;">{{ $availableApartments }} available</p>
            </div>
            <div style="width: 56px; height: 56px; background: #f5f5f7; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #0071e3;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
            </div>
        </div>
    </div>

    <div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.12);">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="color: #86868b; font-size: 14px; font-weight: 500; margin: 0 0 12px 0;">Occupancy Rate</p>
                <h2 style="color: #1d1d1f; font-size: 32px; font-weight: 600; margin: 0;">{{ $occupancyRate }}%</h2>
                <p style="color: #86868b; font-size: 12px; margin: 8px 0 0 0;">{{ $occupiedApartments }} occupied</p>
            </div>
            <div style="width: 56px; height: 56px; background: #f5f5f7; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #0071e3;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
            </div>
        </div>
    </div>
</div>


{{-- Charts Row 1: Revenue & Expenses --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px; margin-bottom: 20px;">
    <div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.12);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h5 style="font-weight: 600; color: #1d1d1f; margin: 0;">Income vs Expenses (Last 6 Months)</h5>
            <a href="{{ route('admin.expenses.index') }}" style="font-size: 13px; color: #0071e3; text-decoration: none;">View Details →</a>
        </div>
        <div style="position: relative; min-height: 300px;">
            <canvas id="revenueExpenseChart"></canvas>
        </div>
    </div>

    <div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.12);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h5 style="font-weight: 600; color: #1d1d1f; margin: 0;">Monthly Profit/Loss</h5>
            <a href="{{ route('admin.expenses.breakeven') }}" style="font-size: 13px; color: #0071e3; text-decoration: none;">Break-Even Analysis →</a>
        </div>
        <div style="position: relative; min-height: 300px;">
            <canvas id="profitChart"></canvas>
        </div>
    </div>
</div>

{{-- Charts Row 2: Expense Breakdown & Payment Status --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 20px;">
    <div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.12);">
        <h5 style="font-weight: 600; color: #1d1d1f; margin: 0 0 20px 0;">Expense Breakdown ({{ date('Y') }})</h5>
        <div style="position: relative; min-height: 280px;">
            <canvas id="expenseBreakdownChart"></canvas>
        </div>
        <div style="display: flex; justify-content: center; gap: 20px; margin-top: 16px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 12px; height: 12px; background: #ff9500; border-radius: 2px;"></div>
                <span style="font-size: 13px; color: #86868b;">Fixed: ${{ number_format($fixedCosts, 0) }}</span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 12px; height: 12px; background: #af52de; border-radius: 2px;"></div>
                <span style="font-size: 13px; color: #86868b;">Variable: ${{ number_format($variableCosts, 0) }}</span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 12px; height: 12px; background: #5856d6; border-radius: 2px;"></div>
                <span style="font-size: 13px; color: #86868b;">Bank: ${{ number_format($bankCosts, 0) }}</span>
            </div>
        </div>
    </div>

    <div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.12);">
        <h5 style="font-weight: 600; color: #1d1d1f; margin: 0 0 20px 0;">Payment Status</h5>
        <div style="position: relative; min-height: 280px;">
            <canvas id="paymentChart"></canvas>
        </div>
    </div>

    <div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.12);">
        <h5 style="font-weight: 600; color: #1d1d1f; margin: 0 0 20px 0;">This Month Summary</h5>
        <div style="display: flex; flex-direction: column; gap: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #f0fdf4; border-radius: 8px;">
                <span style="color: #166534; font-weight: 500;">Income</span>
                <span style="color: #166534; font-weight: 700;">${{ number_format($currentMonthIncome, 2) }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: #fef2f2; border-radius: 8px;">
                <span style="color: #991b1b; font-weight: 500;">Expenses</span>
                <span style="color: #991b1b; font-weight: 700;">${{ number_format($currentMonthExpenses, 2) }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: {{ $currentMonthProfit >= 0 ? '#eff6ff' : '#fff7ed' }}; border-radius: 8px;">
                <span style="color: {{ $currentMonthProfit >= 0 ? '#1e40af' : '#9a3412' }}; font-weight: 500;">{{ $currentMonthProfit >= 0 ? 'Profit' : 'Loss' }}</span>
                <span style="color: {{ $currentMonthProfit >= 0 ? '#1e40af' : '#9a3412' }}; font-weight: 700;">{{ $currentMonthProfit >= 0 ? '+' : '' }}${{ number_format($currentMonthProfit, 2) }}</span>
            </div>
        </div>
        <a href="{{ route('admin.expenses.create') }}" style="display: block; text-align: center; margin-top: 20px; padding: 12px; background: #0071e3; color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
            <i class="bi bi-plus-lg me-2"></i>Add Expense
        </a>
    </div>
</div>

{{-- Chart Scripts --}}
<script>
const chartColors = {
    primary: '#0071E3',
    success: '#34C759',
    warning: '#FF9500',
    danger: '#FF3B30',
    purple: '#AF52DE',
    indigo: '#5856D6'
};

// Income vs Expenses Chart
const revenueExpenseCtx = document.getElementById('revenueExpenseChart');
new Chart(revenueExpenseCtx, {
    type: 'bar',
    data: {
        labels: @json($months),
        datasets: [
            {
                label: 'Income',
                data: @json($monthlyRevenue),
                backgroundColor: chartColors.success,
                borderRadius: 6
            },
            {
                label: 'Expenses',
                data: @json($monthlyExpenses),
                backgroundColor: chartColors.danger,
                borderRadius: 6
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                labels: { padding: 16, color: '#86868B' }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#F5F5F7' },
                ticks: {
                    callback: function(value) {
                        return '$' + value.toLocaleString();
                    }
                }
            },
            x: { grid: { display: false } }
        }
    }
});

// Profit Chart
const profitCtx = document.getElementById('profitChart');
new Chart(profitCtx, {
    type: 'line',
    data: {
        labels: @json($months),
        datasets: [{
            label: 'Profit/Loss',
            data: @json($monthlyProfit),
            borderColor: chartColors.primary,
            backgroundColor: 'rgba(0, 113, 227, 0.1)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: chartColors.primary,
            pointRadius: 6,
            pointHoverRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                grid: { color: '#F5F5F7' },
                ticks: {
                    callback: function(value) {
                        return '$' + value.toLocaleString();
                    }
                }
            },
            x: { grid: { display: false } }
        }
    }
});

// Expense Breakdown Chart
const expenseBreakdownCtx = document.getElementById('expenseBreakdownChart');
new Chart(expenseBreakdownCtx, {
    type: 'doughnut',
    data: {
        labels: ['Fixed Costs', 'Variable Costs', 'Bank/Financial'],
        datasets: [{
            data: [{{ $fixedCosts }}, {{ $variableCosts }}, {{ $bankCosts }}],
            backgroundColor: [chartColors.warning, chartColors.purple, chartColors.indigo],
            borderColor: '#FFFFFF',
            borderWidth: 3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        cutout: '65%'
    }
});

// Payment Status Chart
const paymentCtx = document.getElementById('paymentChart');
new Chart(paymentCtx, {
    type: 'doughnut',
    data: {
        labels: ['Paid', 'Pending', 'Overdue'],
        datasets: [{
            data: [
                {{ $paymentStats['paid'] }},
                {{ $paymentStats['pending'] }},
                {{ $paymentStats['overdue'] }}
            ],
            backgroundColor: [chartColors.success, chartColors.warning, chartColors.danger],
            borderColor: '#FFFFFF',
            borderWidth: 3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { padding: 16, font: { size: 12 }, color: '#86868B' }
            }
        },
        cutout: '60%'
    }
});
</script>

@endsection
