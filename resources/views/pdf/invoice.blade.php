<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice {{ $invoice->invoice_no }}</title>
    <style>
        @page {
            margin: 50px 30px;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.5;
        }

        .header {
            border-bottom: 2px solid #3498db;
            padding-bottom: 20px;
        }

        .company-info {
            float: left;
            width: 60%;
        }

        .invoice-info {
            float: right;
            width: 35%;
            text-align: right;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .section-title {
            background-color: #f8f9fa;
            padding: 8px 15px;
            margin: 20px 0 10px 0;
            font-weight: bold;
            border-left: 4px solid #3498db;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .details-table th {
            background-color: #f8f9fa;
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .details-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .amount-table {
            width: 50%;
            float: right;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .amount-table td {
            padding: 8px 15px;
            border-bottom: 1px solid #eee;
        }

        .amount-table tr.total td {
            font-weight: bold;
            border-top: 2px solid #3498db;
            border-bottom: none;
        }

        .footer {
            border-top: 1px solid #eee;
            font-size: 0.9em;
            text-align: center;
            color: #777;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9em;
        }

        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }

        .status-partial {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-unpaid {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>
    <div class="clearfix header">
        <div class="company-info">
            <h2 style="color: #3498db; margin-bottom: 5px;">{{ Auth::user()->name ?? 'Your Company Name' }}</h2>
            <p style="margin: 0;">{{ $invoice->property->name ?? '' }}</p>
            <p style="margin: 0;">{{ $invoice->property->country ?? '' }}</p>
            <p style="margin: 0;">{{ $invoice->property->provinceRelation->name ?? '' }}</p>
            <p style="margin: 0;"> {{ $invoice->property->districtRelation->name ?? '' }}</p>
            <p style="margin: 0;">{{ $invoice->property->sectorRelation->name ?? '' }}</p>
            <p style="margin: 0;">{{ $invoice->property->cellRelation->name ?? '' }}</p>
            <p style="margin: 0;">{{ $invoice->property->VillageRelation->name ?? '' }}</p>
        </div>

        <div class="invoice-info">
            <h1 style="color: #3498db; margin-bottom: 10px;">INVOICE</h1>
            <p style="margin: 5px 0;"><strong>Invoice #:</strong> {{ $invoice->invoice_no }}</p>
            <p style="margin: 5px 0;"><strong>Date:</strong> {{ $invoice->created_at->format('F j, Y') }}</p>
            <p style="margin: 5px 0;"><strong>Due Date:</strong>
                {{ $invoice->created_at->addDays(30)->format('F j, Y') }}</p>
                
            <p style="margin: 5px 0;">
                <strong>Status:</strong>
                <span class="status status-{{ strtolower($status) }}">
                    {{ $status }}
                </span>
            </p>
        </div>
    </div>

    <div class="clearfix">
        <div style="float: left; width: 50%;">
            <div class="section-title">BILL TO</div>
            <p style="margin: 5px 0;"><strong>{{ $invoice->tenant->tenant_name ?? 'N/A' }}</strong></p>
            <p style="margin: 5px 0;">{{ $invoice->tenant->company_name ?? '' }}</p>
            <p style="margin: 5px 0;">{{ $invoice->tenant->phone ?? '' }}</p>
            <p style="margin: 5px 0;">{{ $invoice->tenant->email ?? '' }}</p>
            <p style="margin: 5px 0;">
                {{ $invoice->tenant->company_tin ? 'TIN: ' . $invoice->tenant->company_tin : '' }}</p>
        </div>

        <div style="float: right; width: 45%;">
            <div class="section-title">UNIT DETAILS</div>
            <p style="margin: 5px 0;">Unit: {{ $invoice->unit->name ?? 'N/A' }}</p>
            <p style="margin: 5px 0;">Room: {{ $invoice->unit->roomNumber ?? '' }}</p>
            <p style="margin: 5px 0;">Rent Type: {{ $invoice->unit->rentTypes ?? '' }}</p>
        </div>
    </div>

    <div class="section-title">INVOICE DETAILS</div>
    <table class="details-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Amount (RWF)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Rent Payment for {{ \Carbon\Carbon::parse($invoice->start_date)->format('m-d-Y') }} To
                    {{ \Carbon\Carbon::parse($invoice->end_date)->format('m-d-Y') }}

                </td>
                <td>{{ number_format($invoice->amount * $invoice->duration_units, 2) }}</td>
            </tr>
            <tr>
                <td>VAT</td>
                <td>{{ number_format($invoice->vat * $invoice->duration_units, 2) }}</td>
            </tr>
            <tr>
                <td>Duration Time</td>
                <td>{{ $invoice->duration_units }} </td>
            </tr>
        </tbody>
    </table>

    @php
        $totalAmount = ($invoice->amount + $invoice->vat)* $invoice->duration_units;
    @endphp

    <table class="amount-table">
        <tr>
            <td>Total:</td>
            <td style="text-align: right;">{{ number_format($totalAmount, 2) }}</td>
        </tr>
        <tr>
            <td>Amount Paid:</td>
            <td style="text-align: right;">-{{ number_format($paidAmount, 2) }}</td>
        </tr>
        <tr class="total">
            <td>Balance Due:</td>
            <td style="text-align: right;">{{ number_format($totalAmount, 2) }}</td>
        </tr>
    </table>


    <div class="clearfix"></div>
    <div class="footer">
        <p>Thank you for your business!</p>
        <p>If you have any questions about this invoice, please contact</p>
        <p><strong>Email:</strong> xxxxxx@gmail.com| <strong>Phone:</strong> 078xxxxxxxx</p>
        <p>Invoice generated on: {{ now()->format('F j, Y \a\t H:i') }}</p>
    </div>
</body>

</html>
