@extends('layouts.admin')

@section('content')

<div style="background: linear-gradient(135deg, #f5f5f7 0%, #ffffff 100%); border-radius: 20px; padding: 32px; margin-bottom: 32px;">
    <h1 style="font-size: 32px; font-weight: 600; color: #1d1d1f; margin: 0;">Dashboard Overview</h1>
    <p style="color: #86868b; margin: 8px 0 0 0;">Manage your property with ease</p>
</div>

{{-- KPI Cards --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 32px;">
    <div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.12);">
        <div style="display: flex; justify-content: space-between; align-items: start;">
            <div>
                <p style="color: #86868b; font-size: 14px; font-weight: 500; margin: 0 0 12px 0;">Total Revenue</p>
                <h2 style="color: #1d1d1f; font-size: 32px; font-weight: 600; margin: 0;">${{ number_format($totalRevenue, 2) }}</h2>
            </div>
            <div style="width: 56px; height: 56px; background: #f5f5f7; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #0071e3;">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </div>
        </div>
    </div>

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
                <p style="color: #86868b; font-size: 12px; margin: 8px 0 0 0;">{{ $availableApartments }} remaining</p>
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
                <p style="color: #86868b; font-size: 14px; font-weight: 500; margin: 0 0 12px 0;">Occupied Rooms</p>
                <h2 style="color: #1d1d1f; font-size: 32px; font-weight: 600; margin: 0;">{{ $occupiedApartments }}</h2>
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


{{-- Charts --}}
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-bottom: 20px;">
    <div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.12);">
        <h5 style="font-weight: 600; color: #1d1d1f; margin: 0 0 20px 0;">Monthly Revenue</h5>
        <div style="position: relative; min-height: 400px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <div style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.12);">
        <h5 style="font-weight: 600; color: #1d1d1f; margin: 0 0 20px 0;">Payment Status</h5>
        <div style="position: relative; min-height: 400px;">
            <canvas id="paymentChart"></canvas>
        </div>
    </div>
</div>

{{-- Chart Scripts --}}
<script>
const chartColors = {
    primary: '#0071E3',
    success: '#34C759',
    warning: '#FF9500',
    danger: '#FF3B30'
};

const revenueCtx = document.getElementById('revenueChart');
new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: @json($months),
        datasets: [{
            label: 'Revenue',
            data: @json($monthlyRevenue),
            backgroundColor: chartColors.primary,
            borderColor: chartColors.primary,
            borderRadius: 8,
            borderSkipped: false
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: '#F5F5F7'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

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
            backgroundColor: [
                chartColors.success,
                chartColors.warning,
                chartColors.danger
            ],
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
                labels: {
                    padding: 16,
                    font: {
                        size: 12
                    },
                    color: '#86868B'
                }
            }
        }
    }
});
</script>

@endsection
