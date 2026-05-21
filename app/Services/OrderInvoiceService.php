<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderInvoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class OrderInvoiceService
{
    public const CUSTOMER = 'customer';
    public const SELLER = 'seller';
    public const ADMIN = 'admin';

    public static function copyTypes(): array
    {
        return [
            self::CUSTOMER => [
                'label' => 'Customer Copy',
                'name' => 'Customer Invoice Copy',
                'color' => '#00265b',
            ],
            self::SELLER => [
                'label' => 'Seller Copy',
                'name' => 'Seller Invoice Copy',
                'color' => '#0f6b2d',
            ],
            self::ADMIN => [
                'label' => 'TTF Admin Copy',
                'name' => 'Admin Invoice Copy',
                'color' => '#5b155c',
            ],
        ];
    }

    public function ensureInvoicesForOrder(Order $order): array
    {
        $invoices = [];

        foreach (array_keys(self::copyTypes()) as $copyType) {
            $invoices[$copyType] = $this->ensureInvoice($order, $copyType);
        }

        return $invoices;
    }

    public function ensureInvoice(Order $order, string $copyType): ?OrderInvoice
    {
        if (!Schema::hasTable('order_invoices')) {
            return null;
        }

        $copyType = array_key_exists($copyType, self::copyTypes()) ? $copyType : self::CUSTOMER;
        $order->loadMissing(['shop.user.addresses.country', 'shop.user.addresses.state', 'shop.user.addresses.city', 'orderDetails.product', 'user']);

        $invoice = OrderInvoice::where('order_id', $order->id)
            ->where('copy_type', $copyType)
            ->first();

        $generatedAt = $invoice && $invoice->generated_at ? Carbon::parse($invoice->generated_at) : now();
        $invoiceNumber = $this->invoiceNumber($order);
        $invoiceName = self::copyTypes()[$copyType]['name'];
        $relativePath = $this->relativePath($order, $copyType);
        $absolutePath = $this->absolutePath($relativePath);

        File::ensureDirectoryExists(dirname($absolutePath));

        $html = view('backend.invoices.pdf', [
            'order' => $order,
            'invoiceCopyType' => $copyType,
            'invoiceCopy' => self::copyTypes()[$copyType],
            'invoiceNumber' => $invoiceNumber,
            'invoiceName' => $invoiceName,
            'invoiceGeneratedAt' => $generatedAt,
            'isPdf' => true,
        ])->render();

        $this->renderPdfToFile($html, $absolutePath);

        return OrderInvoice::updateOrCreate(
            [
                'order_id' => $order->id,
                'copy_type' => $copyType,
            ],
            [
                'invoice_number' => $invoiceNumber,
                'invoice_name' => $invoiceName,
                'file_path' => $relativePath,
                'generated_at' => $generatedAt,
            ]
        );
    }

    public function invoiceNumber(Order $order): string
    {
        $date = $order->date ? Carbon::createFromTimestamp($order->date) : Carbon::parse($order->created_at);

        return 'TTF/INV/' . $date->format('Y') . '/' . $order->code;
    }

    public function absolutePath(string $relativePath): string
    {
        return storage_path('app/' . ltrim($relativePath, '/'));
    }

    public function downloadName(OrderInvoice $invoice): string
    {
        return $this->safeFilename($invoice->invoice_number . '-' . $invoice->copy_type) . '.pdf';
    }

    private function relativePath(Order $order, string $copyType): string
    {
        return 'order-invoices/' . $order->id . '/' . $this->safeFilename($this->invoiceNumber($order) . '-' . $copyType) . '.pdf';
    }

    private function safeFilename(string $value): string
    {
        $value = preg_replace('/[^A-Za-z0-9._-]+/', '-', $value);

        return trim($value, '-');
    }

    private function renderPdfToFile(string $html, string $absolutePath): void
    {
        $this->withMpdfUnserializeWarningSuppressed(function () use ($html, $absolutePath) {
            $mpdf = new \Mpdf\Mpdf($this->pdfConfig());
            $mpdf->showImageErrors = false;
            $mpdf->SetAutoPageBreak(true, 15);
            $mpdf->WriteHTML($html);
            $mpdf->Output($absolutePath, \Mpdf\Output\Destination::FILE);
        });
    }

    private function withMpdfUnserializeWarningSuppressed(callable $callback)
    {
        set_error_handler(function ($severity, $message, $file, $line) {
            if (str_contains($message, 'unserialize(): Extra data starting at offset')) {
                return true;
            }

            return false;
        });

        try {
            return $callback();
        } finally {
            restore_error_handler();
        }
    }

    private function pdfConfig(): array
    {
        $tempDir = storage_path('app/mpdf-invoices-v2/' . uniqid('run-', true));
        File::ensureDirectoryExists($tempDir);

        return [
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
            'directionality' => 'ltr',
        ];
    }
}
