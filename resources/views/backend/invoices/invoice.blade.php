@include('backend.invoices.pdf', [
    'order' => $order,
    'invoiceCopyType' => $invoiceCopyType ?? \App\Services\OrderInvoiceService::CUSTOMER,
    'invoiceCopy' => $invoiceCopy ?? \App\Services\OrderInvoiceService::copyTypes()[$invoiceCopyType ?? \App\Services\OrderInvoiceService::CUSTOMER],
    'invoiceNumber' => $invoiceNumber ?? app(\App\Services\OrderInvoiceService::class)->invoiceNumber($order),
    'invoiceName' => $invoiceName ?? (\App\Services\OrderInvoiceService::copyTypes()[$invoiceCopyType ?? \App\Services\OrderInvoiceService::CUSTOMER]['name']),
    'invoiceGeneratedAt' => $invoiceGeneratedAt ?? now(),
])
