<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Language;
use App\Models\Order;
use App\Services\OrderInvoiceService;
use Session;
use PDF;
use Config;

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

    // Currency
    $currency_code = Session::get(
        'currency_code',
        Currency::findOrFail(get_setting('system_default_currency'))->code
    );

    // Language
    $language_code = Session::get('locale', Config::get('app.locale'));

    // Language Model
    $language = Language::where('code', $language_code)->first();

    // RTL Support
    if (optional($language)->rtl == 1) {
        $direction = 'rtl';
        $text_align = 'right';
        $not_text_align = 'left';
    } else {
        $direction = 'ltr';
        $text_align = 'left';
        $not_text_align = 'right';
    }

    // Font Selection
    if ($currency_code == 'BDT' || $language_code == 'bd') {
        $font_family = "'Hind Siliguri','sans-serif'";
    } elseif ($currency_code == 'KHR' || $language_code == 'kh') {
        $font_family = "'Hanuman','sans-serif'";
    } elseif ($currency_code == 'AMD') {
        $font_family = "'arnamu','sans-serif'";
    } elseif (
        in_array($currency_code, ['AED', 'EGP', 'IQD', 'ROM', 'SDG', 'ILS']) ||
        in_array($language_code, ['sa', 'ir', 'om', 'jo'])
    ) {
        $font_family = "'Baloo Bhaijaan 2','sans-serif'";
    } elseif ($currency_code == 'THB' || $language_code == 'th') {
        $font_family = "'Kanit','sans-serif'";
    } elseif ($currency_code == 'CNY' || $language_code == 'zh') {
        $font_family = "'yahei','sans-serif'";
    } elseif ($currency_code == 'kyat' || $language_code == 'mm') {
        $font_family = "'pyidaungsu','sans-serif'";
    } else {
        $font_family = "'Roboto','sans-serif'";
    }

    // Ensure mPDF temp directory exists
    $tempDir = storage_path('app/mpdf');
    if (!file_exists($tempDir)) {
        mkdir($tempDir, 0777, true);
    }

    // mPDF Config (Important for images + memory)
    $config = [
        'mode' => 'utf-8',
        'format' => 'A4',
        'tempDir' => $tempDir,
        'margin_left' => 0,
        'margin_right' => 0,
        'margin_top' => 0,
        'margin_bottom' => 0,
        'default_font' => 'sans-serif',
        'instanceConfigurator' => function ($mpdf) {
            $mpdf->showImageErrors = false;
            $mpdf->SetAutoPageBreak(true, 15);
        }
    ];

    // Fetch Order
    $order = Order::with('shop')->findOrFail($id);

    // Generate PDF
    return PDF::loadView(
        'backend.invoices.invoice',
        [
            'order' => $order,
            'font_family' => $font_family,
            'direction' => $direction,
            'text_align' => $text_align,
            'not_text_align' => $not_text_align
        ],
        [],
        $config
    )->download('order-' . $order->code . '.pdf');
}

}
