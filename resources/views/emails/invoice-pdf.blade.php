<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $invoice->invoice_no }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; font-size: 12px; }
        .invoice-container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .header h2 { margin-bottom: 5px; }
        .details { margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
        .total { font-weight: bold; text-align: right; margin-top: 10px; }
        .footer { margin-top: 30px; padding-top: 10px; border-top: 1px solid #eee; text-align: center; font-size: 10px; }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <h2>INVOICE</h2>
            <p>#{{ $invoice->invoice_no }}</p>
            <p>Date: {{ now()->format('Y-m-d') }}</p>
        </div>

        <div class="details">
            <p><strong>Tenant:</strong> {{ $invoice->tenant->tenant_name }}</p>
            <p><strong>Property:</strong> {{ $invoice->unit->house->property->name }}</p>
            <p><strong>Unit:</strong> {{ $invoice->unit->name }}</p>
            <p><strong>Period:</strong> {{ $invoice->start_date }} to {{ $invoice->end_date }}</p>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Rent for {{ $invoice->duration_units }} {{ rtrim(ucfirst($invoice->unit->rentTypes ?? ''), 'ly') }}</td>
                    <td>{{ number_format($invoice->amount, 2) }}</td>
                </tr>
                @if($invoice->vat > 0)
                <tr>
                    <td>VAT</td>
                    <td>{{ number_format($invoice->vat, 2) }}</td>
                </tr>
                @endif
            </tbody>
        </table>

        <div class="total">
            <p>Total Amount Due: {{ number_format($invoice->amount + $invoice->vat, 2) }}</p>
        </div>

        <div class="footer">
            <p>Please make payment by {{ now()->addDays(7)->format('Y-m-d') }}</p>
            <p>Thank you for your business!</p>
        </div>
    </div>
</body>
</html>
