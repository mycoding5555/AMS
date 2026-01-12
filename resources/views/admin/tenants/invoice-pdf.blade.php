<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Invoice - {{ $invoiceNumber }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
            background: #fff;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 40px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            border-bottom: 3px solid #0071e3;
            padding-bottom: 20px;
        }
        
        .company-info h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1d1d1f;
            margin-bottom: 5px;
        }
        
        .company-info p {
            color: #86868b;
            font-size: 11px;
        }
        
        .invoice-info {
            text-align: right;
        }
        
        .invoice-info h2 {
            font-size: 24px;
            color: #0071e3;
            margin-bottom: 10px;
        }
        
        .invoice-info p {
            color: #86868b;
            font-size: 11px;
            margin-bottom: 3px;
        }
        
        .invoice-info .invoice-number {
            font-size: 14px;
            font-weight: 600;
            color: #1d1d1f;
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #1d1d1f;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e5e5e7;
        }
        
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label {
            display: table-cell;
            width: 140px;
            padding: 6px 0;
            color: #86868b;
            font-size: 11px;
        }
        
        .info-value {
            display: table-cell;
            padding: 6px 0;
            color: #1d1d1f;
            font-weight: 500;
        }
        
        .tenant-details {
            background: #f5f5f7;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .tenant-name {
            font-size: 18px;
            font-weight: 600;
            color: #1d1d1f;
            margin-bottom: 10px;
        }
        
        .stay-duration-box {
            background: linear-gradient(135deg, #0071e3 0%, #0077ed 100%);
            color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .stay-duration-box h3 {
            font-size: 11px;
            font-weight: 500;
            opacity: 0.9;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .stay-duration-box .duration {
            font-size: 24px;
            font-weight: 700;
        }
        
        .stay-duration-box .dates {
            font-size: 11px;
            opacity: 0.8;
            margin-top: 8px;
        }
        
        .charges-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .charges-table th {
            background: #f5f5f7;
            padding: 12px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            color: #86868b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e5e5e7;
        }
        
        .charges-table td {
            padding: 14px 16px;
            border-bottom: 1px solid #e5e5e7;
            color: #1d1d1f;
        }
        
        .charges-table .description {
            font-weight: 500;
        }
        
        .charges-table .amount {
            text-align: right;
            font-weight: 500;
            font-size: 13px;
        }
        
        .charges-table .total-row {
            background: #f5f5f7;
        }
        
        .charges-table .total-row td {
            font-weight: 700;
            font-size: 14px;
            border-bottom: none;
        }
        
        .charges-table .total-row .amount {
            color: #0071e3;
            font-size: 18px;
        }
        
        .notes-section {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 30px;
        }
        
        .notes-section h4 {
            font-size: 12px;
            font-weight: 600;
            color: #92400e;
            margin-bottom: 8px;
        }
        
        .notes-section p {
            font-size: 11px;
            color: #78350f;
            line-height: 1.6;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e5e7;
            text-align: center;
            color: #86868b;
            font-size: 10px;
        }
        
        .footer p {
            margin-bottom: 5px;
        }
        
        .room-badge {
            display: inline-block;
            background: #e8f4fd;
            color: #0071e3;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .leave-reason {
            display: inline-block;
            background: #fff3e0;
            color: #e65100;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 500;
        }

        /* Print styles */
        @media print {
            .invoice-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        {{-- Header --}}
        <table width="100%" style="margin-bottom: 40px; border-bottom: 3px solid #0071e3; padding-bottom: 20px;">
            <tr>
                <td style="vertical-align: top;">
                    <h1 style="font-size: 28px; font-weight: 700; color: #1d1d1f; margin: 0 0 5px 0;">AMS</h1>
                    <p style="color: #86868b; font-size: 11px; margin: 0;">Apartment Management System</p>
                    <p style="color: #86868b; font-size: 11px; margin: 3px 0 0 0;">Property Management Services</p>
                </td>
                <td style="text-align: right; vertical-align: top;">
                    <h2 style="font-size: 24px; color: #0071e3; margin: 0 0 10px 0;">FINAL INVOICE</h2>
                    <p style="color: #1d1d1f; font-size: 14px; font-weight: 600; margin: 0 0 3px 0;">{{ $invoiceNumber }}</p>
                    <p style="color: #86868b; font-size: 11px; margin: 0;">Generated: {{ $generatedAt->format('M d, Y') }}</p>
                </td>
            </tr>
        </table>

        {{-- Tenant Information --}}
        <table width="100%" style="margin-bottom: 30px;">
            <tr>
                <td width="50%" style="vertical-align: top; padding-right: 20px;">
                    <h3 style="font-size: 14px; font-weight: 600; color: #1d1d1f; margin: 0 0 15px 0; padding-bottom: 8px; border-bottom: 1px solid #e5e5e7;">Tenant Information</h3>
                    <p style="font-size: 16px; font-weight: 600; color: #1d1d1f; margin: 0 0 8px 0;">{{ $tenant->name }}</p>
                    <table width="100%">
                        <tr>
                            <td style="padding: 4px 0; color: #86868b; font-size: 11px; width: 60px;">Email:</td>
                            <td style="padding: 4px 0; color: #1d1d1f; font-size: 11px;">{{ $tenant->email }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 0; color: #86868b; font-size: 11px;">Phone:</td>
                            <td style="padding: 4px 0; color: #1d1d1f; font-size: 11px;">{{ $tenant->phone }}</td>
                        </tr>
                        @if($tenant->address)
                        <tr>
                            <td style="padding: 4px 0; color: #86868b; font-size: 11px; vertical-align: top;">Address:</td>
                            <td style="padding: 4px 0; color: #1d1d1f; font-size: 11px;">{{ $tenant->address }}</td>
                        </tr>
                        @endif
                    </table>
                </td>
                <td width="50%" style="vertical-align: top; padding-left: 20px;">
                    <h3 style="font-size: 14px; font-weight: 600; color: #1d1d1f; margin: 0 0 15px 0; padding-bottom: 8px; border-bottom: 1px solid #e5e5e7;">Room Details</h3>
                    <table width="100%">
                        <tr>
                            <td style="padding: 4px 0; color: #86868b; font-size: 11px; width: 80px;">Room:</td>
                            <td style="padding: 4px 0; color: #1d1d1f; font-size: 11px; font-weight: 600;">{{ $tenant->apartment->apartment_number ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 0; color: #86868b; font-size: 11px;">Floor:</td>
                            <td style="padding: 4px 0; color: #1d1d1f; font-size: 11px;">{{ $tenant->apartment->floor->name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 0; color: #86868b; font-size: 11px;">Monthly Rent:</td>
                            <td style="padding: 4px 0; color: #1d1d1f; font-size: 11px;">${{ number_format($tenant->apartment->monthly_rent ?? 0, 2) }}</td>
                        </tr>
                        @if($tenant->leave_reason)
                        <tr>
                            <td style="padding: 4px 0; color: #86868b; font-size: 11px;">Leave Reason:</td>
                            <td style="padding: 4px 0; color: #e65100; font-size: 11px;">{{ $tenant->leave_reason }}</td>
                        </tr>
                        @endif
                    </table>
                </td>
            </tr>
        </table>

        {{-- Stay Duration --}}
        <table width="100%" style="background: linear-gradient(135deg, #0071e3 0%, #0077ed 100%); border-radius: 8px; margin-bottom: 30px;">
            <tr>
                <td style="padding: 20px; text-align: center; color: white;">
                    <p style="font-size: 11px; font-weight: 500; opacity: 0.9; margin: 0 0 5px 0; text-transform: uppercase; letter-spacing: 1px;">Total Stay Duration</p>
                    <p style="font-size: 24px; font-weight: 700; margin: 0;">{{ $tenant->getStayDurationFormatted() }}</p>
                    <p style="font-size: 11px; opacity: 0.8; margin: 8px 0 0 0;">
                        {{ $tenant->move_in_date->format('M d, Y') }} — {{ $tenant->move_out_date->format('M d, Y') }}
                        ({{ $tenant->getStayDurationDays() }} days)
                    </p>
                </td>
            </tr>
        </table>

        {{-- Charges Table --}}
        <h3 style="font-size: 14px; font-weight: 600; color: #1d1d1f; margin: 0 0 15px 0; padding-bottom: 8px; border-bottom: 1px solid #e5e5e7;">Charges Summary</h3>
        <table width="100%" style="border-collapse: collapse; margin-bottom: 30px;">
            <thead>
                <tr style="background: #f5f5f7;">
                    <th style="padding: 12px 16px; text-align: left; font-size: 11px; font-weight: 600; color: #86868b; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #e5e5e7;">Description</th>
                    <th style="padding: 12px 16px; text-align: right; font-size: 11px; font-weight: 600; color: #86868b; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #e5e5e7;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 14px 16px; border-bottom: 1px solid #e5e5e7; color: #1d1d1f;">
                        <strong>Rent Charges</strong><br>
                        <span style="font-size: 10px; color: #86868b;">Total rent for {{ $tenant->getStayDurationDays() }} days</span>
                    </td>
                    <td style="padding: 14px 16px; border-bottom: 1px solid #e5e5e7; text-align: right; font-weight: 500; font-size: 13px; color: #1d1d1f;">
                        ${{ number_format($tenant->total_rent_paid, 2) }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 14px 16px; border-bottom: 1px solid #e5e5e7; color: #1d1d1f;">
                        <strong>Utility Charges</strong><br>
                        <span style="font-size: 10px; color: #86868b;">Water, electricity, internet, etc.</span>
                    </td>
                    <td style="padding: 14px 16px; border-bottom: 1px solid #e5e5e7; text-align: right; font-weight: 500; font-size: 13px; color: #1d1d1f;">
                        ${{ number_format($tenant->final_utility_charges, 2) }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 14px 16px; border-bottom: 1px solid #e5e5e7; color: #1d1d1f;">
                        <strong>Other Charges</strong><br>
                        <span style="font-size: 10px; color: #86868b;">Damages, cleaning fees, etc.</span>
                    </td>
                    <td style="padding: 14px 16px; border-bottom: 1px solid #e5e5e7; text-align: right; font-weight: 500; font-size: 13px; color: #1d1d1f;">
                        ${{ number_format($tenant->final_other_charges, 2) }}
                    </td>
                </tr>
                <tr style="background: #f5f5f7;">
                    <td style="padding: 16px; font-weight: 700; font-size: 14px; color: #1d1d1f;">
                        TOTAL AMOUNT
                    </td>
                    <td style="padding: 16px; text-align: right; font-weight: 700; font-size: 18px; color: #0071e3;">
                        ${{ number_format($tenant->getTotalFinalAmount(), 2) }}
                    </td>
                </tr>
            </tbody>
        </table>

        {{-- Notes --}}
        @if($tenant->invoice_notes)
        <table width="100%" style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; margin-bottom: 30px;">
            <tr>
                <td style="padding: 16px;">
                    <h4 style="font-size: 12px; font-weight: 600; color: #92400e; margin: 0 0 8px 0;">Additional Notes</h4>
                    <p style="font-size: 11px; color: #78350f; line-height: 1.6; margin: 0;">{{ $tenant->invoice_notes }}</p>
                </td>
            </tr>
        </table>
        @endif

        {{-- Footer --}}
        <table width="100%" style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e5e5e7;">
            <tr>
                <td style="text-align: center; color: #86868b; font-size: 10px;">
                    <p style="margin: 0 0 5px 0;">This is a computer-generated invoice.</p>
                    <p style="margin: 0 0 5px 0;">Thank you for being our valued tenant.</p>
                    <p style="margin: 0;">AMS - Apartment Management System | Generated on {{ $generatedAt->format('F d, Y \a\t h:i A') }}</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
