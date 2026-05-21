<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderInvoiceService;

class InvoiceController extends Controller
{
   // Download Invoice
public function invoice_download($id)
{
    $order = Order::findOrFail($id);
    $user = auth()->user();

    if ($user && !in_array($user->user_type, ['admin', 'staff']) && (int) $order->user_id !== (int) $user->id) {
        abort(403);
    }

    $invoiceService = app(OrderInvoiceService::class);
    try {
        $invoice = $invoiceService->ensureInvoice($order, OrderInvoiceService::CUSTOMER);

        if ($invoice) {
            return response()->download(
                $invoiceService->absolutePath($invoice->file_path),
                $invoiceService->downloadName($invoice)
            );
        }
    } catch (\Exception $e) {
        \Log::error('Customer invoice copy download failed: ' . $e->getMessage(), [
            'order_id' => $order->id,
        ]);
    }

    ini_set('memory_limit', '512M');

    // Ensure mPDF temp directory exists
    $tempDir = storage_path('app/mpdf-invoices-v2/' . uniqid('run-', true));
    if (!file_exists($tempDir)) {
        mkdir($tempDir, 0777, true);
    }

    // mPDF Config (Important for images + memory)
    $config = [
        'mode' => 'utf-8',
        'format' => 'A4',
        'tempDir' => $tempDir,
        'font_path' => public_path('assets/fonts/'),
        'font_data' => [
            'roboto' => [
                'R' => 'Roboto-Regular.ttf',
                'useOTL' => 0xFF,
                'useKashida' => 75,
            ],
        ],
        'margin_left' => 0,
        'margin_right' => 0,
        'margin_top' => 0,
        'margin_bottom' => 0,
        'default_font' => 'roboto',
        'instanceConfigurator' => function ($mpdf) {
            $mpdf->showImageErrors = false;
            $mpdf->SetAutoPageBreak(true, 15);
        }
    ];

    // Fetch Order
    $order = Order::with('shop')->findOrFail($id);

    $html = view('backend.invoices.invoice', [
        'order' => $order,
    ])->render();

    set_error_handler(function ($severity, $message, $file, $line) {
        if (str_contains($message, 'unserialize(): Extra data starting at offset')) {
            return true;
        }

        return false;
    });

    try {
        $mpdf = new \Mpdf\Mpdf($config);
        $mpdf->showImageErrors = false;
        $mpdf->SetAutoPageBreak(true, 15);
        $mpdf->WriteHTML($html);
        $output = $mpdf->Output('order-' . $order->code . '.pdf', \Mpdf\Output\Destination::STRING_RETURN);
    } finally {
        restore_error_handler();
    }

    return response($output, 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="order-' . $order->code . '.pdf"',
    ]);
}

}
