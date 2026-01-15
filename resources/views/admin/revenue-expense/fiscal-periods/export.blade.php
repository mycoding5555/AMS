@extends('layouts.admin')

@section('content')

<div style="background: linear-gradient(135deg, #f5f5f7 0%, #ffffff 100%); border-radius: 20px; padding: 32px; margin-bottom: 32px;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div>
            <h1 style="font-size: 32px; font-weight: 600; color: #1d1d1f; margin: 0;">Closing Balance Report</h1>
            <p style="color: #86868b; margin: 8px 0 0 0;">{{ $fiscalPeriod->name }} | {{ $fiscalPeriod->opening_date->format('M d, Y') }} - {{ $fiscalPeriod->closing_date->format('M d, Y') }}</p>
        </div>
        <div style="display: flex; gap: 12px; flex-wrap: wrap;">
            <a href="{{ route('admin.fiscal-periods.download', $fiscalPeriod) }}" style="padding: 12px 24px; background: #34c759; color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="bi bi-download me-2"></i>Download PDF
            </a>
            <button onclick="window.print()" style="padding: 12px 24px; background: #5856d6; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                <i class="bi bi-printer me-2"></i>Print
            </button>
            <a href="{{ route('admin.fiscal-periods.show', $fiscalPeriod) }}" style="padding: 12px 24px; background: #f5f5f7; color: #1d1d1f; border-radius: 8px; text-decoration: none; font-weight: 600;">
                <i class="bi bi-arrow-left me-2"></i>Back
            </a>
        </div>
    </div>
</div>

{{-- Report Content --}}
<div id="report-content" style="background: white; border: 1px solid #d5d5d7; border-radius: 12px; padding: 32px;">
    
    {{-- Header --}}
    <div style="text-align: center; margin-bottom: 32px; padding-bottom: 24px; border-bottom: 2px solid #1d1d1f;">
        <h1 style="font-size: 28px; font-weight: 700; color: #1d1d1f; margin: 0;">CLOSING BALANCE REPORT</h1>
        <h2 style="font-size: 20px; font-weight: 600; color: #86868b; margin: 8px 0 0 0;">{{ $fiscalPeriod->name }}</h2>
        <p style="color: #86868b; margin: 8px 0 0 0;">
            Period: {{ $fiscalPeriod->opening_date->format('F d, Y') }} to {{ $fiscalPeriod->closing_date->format('F d, Y') }}
        </p>
        <p style="color: #86868b; margin: 4px 0 0 0;">
            Closed on: {{ $fiscalPeriod->closed_at?->format('F d, Y h:i A') ?? 'Not closed' }}
        </p>
    </div>

    {{-- Executive Summary --}}
    <div style="margin-bottom: 32px;">
        <h3 style="font-size: 18px; font-weight: 700; color: #1d1d1f; margin: 0 0 16px 0; padding-bottom: 8px; border-bottom: 1px solid #d5d5d7;">EXECUTIVE SUMMARY</h3>
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
            <div style="padding: 16px; background: #f5f5f7; border-radius: 8px;">
                <p style="color: #86868b; font-size: 13px; margin: 0;">Opening Balance</p>
                <p style="font-size: 24px; font-weight: 700; color: #5856d6; margin: 4px 0 0 0;">${{ number_format($summary['opening_balance'], 2) }}</p>
            </div>
            <div style="padding: 16px; background: #f5f5f7; border-radius: 8px;">
                <p style="color: #86868b; font-size: 13px; margin: 0;">Closing Balance</p>
                <p style="font-size: 24px; font-weight: 700; color: #1d1d1f; margin: 4px 0 0 0;">${{ number_format($summary['closing_balance'], 2) }}</p>
            </div>
            <div style="padding: 16px; background: #dcfce7; border-radius: 8px;">
                <p style="color: #166534; font-size: 13px; margin: 0;">Total Revenue</p>
                <p style="font-size: 24px; font-weight: 700; color: #166534; margin: 4px 0 0 0;">${{ number_format($summary['total_income'], 2) }}</p>
            </div>
            <div style="padding: 16px; background: #fee2e2; border-radius: 8px;">
                <p style="color: #991b1b; font-size: 13px; margin: 0;">Total Expenses</p>
                <p style="font-size: 24px; font-weight: 700; color: #991b1b; margin: 4px 0 0 0;">${{ number_format($summary['total_expenses'], 2) }}</p>
            </div>
            <div style="padding: 16px; background: {{ $summary['net_income'] >= 0 ? '#dbeafe' : '#fee2e2' }}; border-radius: 8px; grid-column: span 2;">
                <p style="color: {{ $summary['net_income'] >= 0 ? '#1e40af' : '#991b1b' }}; font-size: 13px; margin: 0;">Net {{ $summary['net_income'] >= 0 ? 'Profit' : 'Loss' }}</p>
                <p style="font-size: 32px; font-weight: 700; color: {{ $summary['net_income'] >= 0 ? '#1e40af' : '#991b1b' }}; margin: 4px 0 0 0;">
                    {{ $summary['net_income'] >= 0 ? '+' : '' }}${{ number_format($summary['net_income'], 2) }}
                </p>
            </div>
        </div>
    </div>

    {{-- Income Statement --}}
    <div style="margin-bottom: 32px;">
        <h3 style="font-size: 18px; font-weight: 700; color: #1d1d1f; margin: 0 0 16px 0; padding-bottom: 8px; border-bottom: 1px solid #d5d5d7;">INCOME STATEMENT (Revenue & Expenses)</h3>
        
        {{-- Revenue --}}
        <div style="margin-bottom: 24px;">
            <h4 style="font-size: 14px; font-weight: 600; color: #34c759; margin: 0 0 12px 0; text-transform: uppercase;">Revenue</h4>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f5f5f7;">
                        <th style="text-align: left; padding: 8px 12px; font-weight: 600; color: #86868b; font-size: 13px;">Date</th>
                        <th style="text-align: left; padding: 8px 12px; font-weight: 600; color: #86868b; font-size: 13px;">Category</th>
                        <th style="text-align: left; padding: 8px 12px; font-weight: 600; color: #86868b; font-size: 13px;">Description</th>
                        <th style="text-align: right; padding: 8px 12px; font-weight: 600; color: #86868b; font-size: 13px;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($incomeAccounts as $account)
                    <tr style="border-bottom: 1px solid #f5f5f7;">
                        <td style="padding: 8px 12px; color: #1d1d1f;">{{ $account->transaction_date->format('M d, Y') }}</td>
                        <td style="padding: 8px 12px; color: #1d1d1f;">{{ $account->category_label }}</td>
                        <td style="padding: 8px 12px; color: #86868b;">{{ $account->description ?? '-' }}</td>
                        <td style="padding: 8px 12px; text-align: right; font-weight: 600; color: #34c759;">${{ number_format($account->amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding: 16px; text-align: center; color: #86868b;">No revenue entries</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr style="background: #dcfce7;">
                        <td colspan="3" style="padding: 12px; font-weight: 700; color: #166534;">Total Revenue</td>
                        <td style="padding: 12px; text-align: right; font-weight: 700; color: #166534;">${{ number_format($summary['total_income'], 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Expenses --}}
        <div>
            <h4 style="font-size: 14px; font-weight: 600; color: #ff3b30; margin: 0 0 12px 0; text-transform: uppercase;">Expenses</h4>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f5f5f7;">
                        <th style="text-align: left; padding: 8px 12px; font-weight: 600; color: #86868b; font-size: 13px;">Date</th>
                        <th style="text-align: left; padding: 8px 12px; font-weight: 600; color: #86868b; font-size: 13px;">Category</th>
                        <th style="text-align: left; padding: 8px 12px; font-weight: 600; color: #86868b; font-size: 13px;">Description</th>
                        <th style="text-align: left; padding: 8px 12px; font-weight: 600; color: #86868b; font-size: 13px;">Type</th>
                        <th style="text-align: right; padding: 8px 12px; font-weight: 600; color: #86868b; font-size: 13px;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenseAccounts as $account)
                    <tr style="border-bottom: 1px solid #f5f5f7;">
                        <td style="padding: 8px 12px; color: #1d1d1f;">{{ $account->transaction_date->format('M d, Y') }}</td>
                        <td style="padding: 8px 12px; color: #1d1d1f;">{{ $account->category_label }}</td>
                        <td style="padding: 8px 12px; color: #86868b;">{{ $account->description ?? '-' }}</td>
                        <td style="padding: 8px 12px; color: #86868b;">{{ $account->cost_type_label }}</td>
                        <td style="padding: 8px 12px; text-align: right; font-weight: 600; color: #ff3b30;">${{ number_format($account->amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="padding: 16px; text-align: center; color: #86868b;">No expense entries</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr style="background: #fee2e2;">
                        <td colspan="4" style="padding: 12px; font-weight: 700; color: #991b1b;">Total Expenses</td>
                        <td style="padding: 12px; text-align: right; font-weight: 700; color: #991b1b;">${{ number_format($summary['total_expenses'], 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Balance Sheet --}}
    <div style="margin-bottom: 32px;">
        <h3 style="font-size: 18px; font-weight: 700; color: #1d1d1f; margin: 0 0 16px 0; padding-bottom: 8px; border-bottom: 1px solid #d5d5d7;">BALANCE SHEET</h3>
        
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px;">
            {{-- Assets Column --}}
            <div>
                <h4 style="font-size: 14px; font-weight: 600; color: #34c759; margin: 0 0 12px 0; text-transform: uppercase;">Assets</h4>
                <table style="width: 100%; border-collapse: collapse;">
                    @forelse($assets as $asset)
                    <tr style="border-bottom: 1px solid #f5f5f7;">
                        <td style="padding: 8px 0; color: #1d1d1f;">{{ $asset->name }}</td>
                        <td style="padding: 8px 0; text-align: right; font-weight: 600; color: #34c759;">${{ number_format($asset->amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" style="padding: 16px; text-align: center; color: #86868b;">No assets</td>
                    </tr>
                    @endforelse
                    <tr style="border-top: 2px solid #34c759; background: #dcfce7;">
                        <td style="padding: 12px 0; font-weight: 700; color: #166534;">Total Assets</td>
                        <td style="padding: 12px 0; text-align: right; font-weight: 700; color: #166534;">${{ number_format($summary['total_assets'], 2) }}</td>
                    </tr>
                </table>
            </div>

            {{-- Liabilities + Equity Column --}}
            <div>
                <h4 style="font-size: 14px; font-weight: 600; color: #ff3b30; margin: 0 0 12px 0; text-transform: uppercase;">Liabilities</h4>
                <table style="width: 100%; border-collapse: collapse; margin-bottom: 24px;">
                    @forelse($liabilities as $liability)
                    <tr style="border-bottom: 1px solid #f5f5f7;">
                        <td style="padding: 8px 0; color: #1d1d1f;">{{ $liability->name }}</td>
                        <td style="padding: 8px 0; text-align: right; font-weight: 600; color: #ff3b30;">${{ number_format($liability->amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" style="padding: 16px; text-align: center; color: #86868b;">No liabilities</td>
                    </tr>
                    @endforelse
                    <tr style="border-top: 2px solid #ff3b30; background: #fee2e2;">
                        <td style="padding: 12px 0; font-weight: 700; color: #991b1b;">Total Liabilities</td>
                        <td style="padding: 12px 0; text-align: right; font-weight: 700; color: #991b1b;">${{ number_format($summary['total_liabilities'], 2) }}</td>
                    </tr>
                </table>

                <h4 style="font-size: 14px; font-weight: 600; color: #007aff; margin: 0 0 12px 0; text-transform: uppercase;">Equity</h4>
                <table style="width: 100%; border-collapse: collapse;">
                    @forelse($equity as $eq)
                    <tr style="border-bottom: 1px solid #f5f5f7;">
                        <td style="padding: 8px 0; color: #1d1d1f;">{{ $eq->name }}</td>
                        <td style="padding: 8px 0; text-align: right; font-weight: 600; color: #007aff;">${{ number_format($eq->amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" style="padding: 16px; text-align: center; color: #86868b;">No equity</td>
                    </tr>
                    @endforelse
                    <tr style="border-top: 2px solid #007aff; background: #dbeafe;">
                        <td style="padding: 12px 0; font-weight: 700; color: #1e40af;">Total Equity</td>
                        <td style="padding: 12px 0; text-align: right; font-weight: 700; color: #1e40af;">${{ number_format($summary['total_equity'], 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Final Balance Box --}}
    <div style="background: linear-gradient(135deg, #1d1d1f 0%, #3d3d3f 100%); border-radius: 16px; padding: 32px; text-align: center; color: white;">
        <p style="font-size: 14px; opacity: 0.8; margin: 0; text-transform: uppercase; letter-spacing: 1px;">Final Closing Balance</p>
        <p style="font-size: 48px; font-weight: 700; margin: 16px 0 0 0;">${{ number_format($summary['closing_balance'], 2) }}</p>
        <p style="font-size: 14px; opacity: 0.6; margin: 16px 0 0 0;">
            As of {{ $fiscalPeriod->closing_date->format('F d, Y') }}
        </p>
    </div>

    {{-- Footer --}}
    <div style="margin-top: 32px; padding-top: 24px; border-top: 1px solid #d5d5d7; text-align: center;">
        <p style="color: #86868b; font-size: 13px; margin: 0;">
            Report generated on {{ now()->format('F d, Y h:i A') }}
        </p>
        @if($fiscalPeriod->closedBy)
        <p style="color: #86868b; font-size: 13px; margin: 4px 0 0 0;">
            Closed by: {{ $fiscalPeriod->closedBy->name }}
        </p>
        @endif
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #report-content, #report-content * {
        visibility: visible;
    }
    #report-content {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>

@endsection
