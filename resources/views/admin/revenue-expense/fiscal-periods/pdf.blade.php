<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Closing Balance Report - {{ $fiscalPeriod->name }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0 0 5px 0;
            color: #1d1d1f;
        }
        .header h2 {
            font-size: 16px;
            margin: 0;
            color: #666;
            font-weight: normal;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #888;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #1d1d1f;
            border-bottom: 1px solid #ddd;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .summary-grid {
            display: table;
            width: 100%;
        }
        .summary-item {
            display: table-cell;
            width: 25%;
            padding: 10px;
            text-align: center;
            background: #f5f5f7;
            border-right: 1px solid #ddd;
        }
        .summary-item:last-child {
            border-right: none;
        }
        .summary-item .label {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
        }
        .summary-item .value {
            font-size: 18px;
            font-weight: bold;
            margin-top: 5px;
        }
        .positive { color: #34c759; }
        .negative { color: #ff3b30; }
        .neutral { color: #007aff; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            padding: 8px 10px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        table th {
            background: #f5f5f7;
            font-weight: bold;
            color: #666;
            font-size: 10px;
            text-transform: uppercase;
        }
        table td.amount {
            text-align: right;
            font-weight: bold;
        }
        .total-row {
            background: #f0f0f0;
            font-weight: bold;
        }
        .total-row td {
            border-top: 2px solid #333;
        }
        .final-balance {
            background: #1d1d1f;
            color: white;
            padding: 30px;
            text-align: center;
            margin-top: 30px;
        }
        .final-balance .label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
        }
        .final-balance .value {
            font-size: 36px;
            font-weight: bold;
            margin-top: 10px;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #888;
            font-size: 10px;
        }
        .balance-sheet-grid {
            display: table;
            width: 100%;
        }
        .balance-sheet-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 15px;
        }
        .balance-sheet-col:last-child {
            padding-right: 0;
            padding-left: 15px;
        }
        .sub-section {
            margin-bottom: 20px;
        }
        .sub-section-title {
            font-size: 11px;
            font-weight: bold;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>CLOSING BALANCE REPORT</h1>
        <h2>{{ $fiscalPeriod->name }}</h2>
        <p>Period: {{ $fiscalPeriod->opening_date->format('F d, Y') }} to {{ $fiscalPeriod->closing_date->format('F d, Y') }}</p>
        @if($fiscalPeriod->closed_at)
        <p>Closed on: {{ $fiscalPeriod->closed_at->format('F d, Y h:i A') }}</p>
        @endif
    </div>

    <div class="section">
        <div class="section-title">EXECUTIVE SUMMARY</div>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="label">Opening Balance</div>
                <div class="value neutral">${{ number_format($summary['opening_balance'], 2) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Total Revenue</div>
                <div class="value positive">${{ number_format($summary['total_income'], 2) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Total Expenses</div>
                <div class="value negative">${{ number_format($summary['total_expenses'], 2) }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Net {{ $summary['net_income'] >= 0 ? 'Profit' : 'Loss' }}</div>
                <div class="value {{ $summary['net_income'] >= 0 ? 'positive' : 'negative' }}">
                    {{ $summary['net_income'] >= 0 ? '+' : '' }}${{ number_format($summary['net_income'], 2) }}
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">INCOME STATEMENT</div>
        
        <div class="sub-section">
            <div class="sub-section-title">Revenue</div>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th style="text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($incomeAccounts as $account)
                    <tr>
                        <td>{{ $account->transaction_date->format('M d, Y') }}</td>
                        <td>{{ $account->category_label }}</td>
                        <td>{{ $account->description ?? '-' }}</td>
                        <td class="amount positive">${{ number_format($account->amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: #888;">No revenue entries</td>
                    </tr>
                    @endforelse
                    <tr class="total-row">
                        <td colspan="3">Total Revenue</td>
                        <td class="amount positive">${{ number_format($summary['total_income'], 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="sub-section">
            <div class="sub-section-title">Expenses</div>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Type</th>
                        <th style="text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenseAccounts as $account)
                    <tr>
                        <td>{{ $account->transaction_date->format('M d, Y') }}</td>
                        <td>{{ $account->category_label }}</td>
                        <td>{{ $account->description ?? '-' }}</td>
                        <td>{{ $account->cost_type_label }}</td>
                        <td class="amount negative">${{ number_format($account->amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #888;">No expense entries</td>
                    </tr>
                    @endforelse
                    <tr class="total-row">
                        <td colspan="4">Total Expenses</td>
                        <td class="amount negative">${{ number_format($summary['total_expenses'], 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="section">
        <div class="section-title">BALANCE SHEET</div>
        <div class="balance-sheet-grid">
            <div class="balance-sheet-col">
                <div class="sub-section-title">Assets</div>
                <table>
                    @forelse($assets as $asset)
                    <tr>
                        <td>{{ $asset->name }}</td>
                        <td class="amount positive">${{ number_format($asset->amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" style="text-align: center; color: #888;">No assets</td>
                    </tr>
                    @endforelse
                    <tr class="total-row">
                        <td>Total Assets</td>
                        <td class="amount positive">${{ number_format($summary['total_assets'], 2) }}</td>
                    </tr>
                </table>
            </div>
            <div class="balance-sheet-col">
                <div class="sub-section-title">Liabilities</div>
                <table>
                    @forelse($liabilities as $liability)
                    <tr>
                        <td>{{ $liability->name }}</td>
                        <td class="amount negative">${{ number_format($liability->amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" style="text-align: center; color: #888;">No liabilities</td>
                    </tr>
                    @endforelse
                    <tr class="total-row">
                        <td>Total Liabilities</td>
                        <td class="amount negative">${{ number_format($summary['total_liabilities'], 2) }}</td>
                    </tr>
                </table>

                <div class="sub-section-title" style="margin-top: 20px;">Equity</div>
                <table>
                    @forelse($equity as $eq)
                    <tr>
                        <td>{{ $eq->name }}</td>
                        <td class="amount neutral">${{ number_format($eq->amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="2" style="text-align: center; color: #888;">No equity</td>
                    </tr>
                    @endforelse
                    <tr class="total-row">
                        <td>Total Equity</td>
                        <td class="amount neutral">${{ number_format($summary['total_equity'], 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="final-balance">
        <div class="label">Final Closing Balance</div>
        <div class="value">${{ number_format($summary['closing_balance'], 2) }}</div>
    </div>

    <div class="footer">
        <p>Report generated on {{ now()->format('F d, Y h:i A') }}</p>
        @if($fiscalPeriod->closedBy)
        <p>Closed by: {{ $fiscalPeriod->closedBy->name }}</p>
        @endif
    </div>
</body>
</html>
