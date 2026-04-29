@component('mail::message')
# Invoice #{{ $invoice->invoice_no }}

Dear {{ $invoice->tenant->tenant_name }},

Please find attached your invoice for {{ $invoice->unit->house->property->name }} (Unit: {{ $invoice->unit->name }}).

**Period:** {{ $invoice->start_date }} to {{ $invoice->end_date }}
**Total Amount Due:** {{ number_format($invoice->amount + $invoice->vat, 2) }}

Payment is due by {{ now()->addDays(7)->format('Y-m-d') }}.

Thank you for your prompt payment.

Regards,
{{ config('app.name') }}
@endcomponent
