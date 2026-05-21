<?php

namespace App\Exports;

use App\Services\OrderInvoiceService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AllOrdersReportExport implements FromCollection, WithHeadings
{
    protected $orders;
    protected $invoiceService;

    public function __construct($orders, OrderInvoiceService $invoiceService)
    {
        $this->orders = $orders;
        $this->invoiceService = $invoiceService;
    }

    public function collection()
    {
        $data = new Collection();

        foreach ($this->orders as $key => $order) {
            $shipping = json_decode($order->shipping_address);
            $customerInvoice = $order->invoices->firstWhere('copy_type', OrderInvoiceService::CUSTOMER);
            $shippingTotal = (float) $order->orderDetails->sum('shipping_cost');
            $itemsSubtotal = (float) $order->orderDetails->sum('price');
            $addonsSubtotal = (float) $order->orderDetails->sum('addon_price');
            $productNames = [];
            $productSkus = [];
            $productQuantities = [];
            $productLines = [];

            foreach ($order->orderDetails as $detail) {
                if (!$detail->product) {
                    continue;
                }

                $stock = $detail->variation
                    ? $detail->product->stocks->firstWhere('variant', $detail->variation)
                    : $detail->product->stocks->first();
                $sku = $stock->sku ?? '';
                $lineName = $detail->product->name . ($detail->variation ? ' (' . $detail->variation . ')' : '');

                $productNames[] = $lineName;
                $productSkus[] = $sku;
                $productQuantities[] = $detail->quantity;
                $productLines[] = $lineName
                    . ' | SKU: ' . ($sku ?: '-')
                    . ' | Qty: ' . $detail->quantity
                    . ' | Unit: ' . number_format($detail->quantity > 0 ? $detail->price / $detail->quantity : $detail->price, 2)
                    . ' | Line Total: ' . number_format($detail->price + (float) $detail->addon_price, 2);
            }

            $data->push([
                'Sno.' => $key + 1,
                'Order Code' => $order->code,
                'Invoice No.' => optional($order->invoices->first())->invoice_number,
                'Order Date' => $order->created_at ? $order->created_at->format('d M Y h:i A') : '',
                'Number of Products' => $order->orderDetails->count(),
                'Product Names' => implode("\n", $productNames),
                'Product SKUs' => implode("\n", array_filter($productSkus)),
                'Product Quantities' => implode(', ', $productQuantities),
                'Product Line Details' => implode("\n", $productLines),
                'Customer' => optional($order->user)->name ?: ('Guest ' . $order->guest_id),
                'Customer Email' => optional($order->user)->email ?: ($shipping->email ?? ''),
                'Customer Phone' => optional($order->user)->phone ?: ($shipping->phone ?? ''),
                'Shipping Name' => $shipping->name ?? '',
                'Shipping Email' => $shipping->email ?? '',
                'Shipping Phone' => $shipping->phone ?? '',
                'Shipping Address' => $this->shippingAddress($shipping),
                'Seller' => optional($order->shop)->name ?: translate('Inhouse Order'),
                'Seller Email' => optional(optional($order->shop)->user)->email ?: (optional($order->shop)->email ?? ''),
                'Seller Phone' => optional($order->shop)->phone ?: optional(optional($order->shop)->user)->phone,
                'Seller Address' => $this->sellerAddress($order),
                'Items Subtotal' => number_format($itemsSubtotal, 2, '.', ''),
                'Addons Subtotal' => number_format($addonsSubtotal, 2, '.', ''),
                'Shipping Total' => number_format($shippingTotal, 2, '.', ''),
                'Coupon Discount' => number_format((float) $order->coupon_discount, 2, '.', ''),
                'Grand Total' => number_format((float) $order->grand_total, 2, '.', ''),
                'Delivery Status' => translate(ucfirst(str_replace('_', ' ', $order->delivery_status))),
                'Payment Method' => translate(ucfirst(str_replace('_', ' ', $order->payment_type))),
                'Payment Status' => $order->payment_status == 'paid' ? translate('Paid') : translate('Unpaid'),
                'Customer Invoice PDF' => $customerInvoice ? route('invoice.download', $order->id) : '',
                'Customer Invoice File' => $customerInvoice ? $this->invoiceService->downloadName($customerInvoice) : '',
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Sno.',
            'Order Code',
            'Invoice No.',
            'Order Date',
            'Number of Products',
            'Product Names',
            'Product SKUs',
            'Product Quantities',
            'Product Line Details',
            'Customer',
            'Customer Email',
            'Customer Phone',
            'Shipping Name',
            'Shipping Email',
            'Shipping Phone',
            'Shipping Address',
            'Seller',
            'Seller Email',
            'Seller Phone',
            'Seller Address',
            'Items Subtotal',
            'Addons Subtotal',
            'Shipping Total',
            'Coupon Discount',
            'Grand Total',
            'Delivery Status',
            'Payment Method',
            'Payment Status',
            'Customer Invoice PDF',
            'Customer Invoice File',
        ];
    }

    private function shippingAddress($shipping): string
    {
        if (!$shipping) {
            return '';
        }

        return collect([
            $shipping->address ?? null,
            $shipping->street ?? null,
            $shipping->city ?? null,
            $shipping->state ?? null,
            $shipping->postal_code ?? null,
            $shipping->country ?? null,
        ])->filter()->implode(', ');
    }

    private function sellerAddress($order): string
    {
        $shop = $order->shop;
        if (!$shop) {
            return '';
        }

        return collect([
            $shop->address ?? null,
            optional($shop->city)->name,
            $shop->postal_code ?? null,
            optional($shop->state)->name,
            optional($shop->country)->name,
        ])->filter()->implode(', ');
    }
}
