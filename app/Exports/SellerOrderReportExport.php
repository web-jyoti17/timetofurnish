<?php
namespace App\Exports;

use App\Models\OrderDetail;
use App\Models\Shop;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;
use Auth;

class SellerOrderReportExport implements FromCollection, WithHeadings
{
    protected $paymentStatus;
    protected $deliveryStatus;
    protected $sortSearch;

    public function __construct($paymentStatus, $deliveryStatus, $sortSearch)
    {
        $this->paymentStatus = $paymentStatus;
        $this->deliveryStatus = $deliveryStatus;
        $this->sortSearch = $sortSearch;
    }

    public function collection()
    {
        $data = collect([]);
        $orders = DB::table('orders')
            ->orderBy('id', 'desc')
            ->where('seller_id', Auth::user()->id)
            ->select('orders.id')
            ->distinct();

        if ($this->paymentStatus != null) {
            $orders->where('payment_status', $this->paymentStatus);
        }

        if ($this->deliveryStatus != null) {
            $orders->where('delivery_status', $this->deliveryStatus);
        }

        if ($this->sortSearch != null) {
            $orders->where('code', 'like', '%' . $this->sortSearch . '%');
        }
        $orders = $orders->get();
        foreach($orders as $key =>  $order_id){
            $order = \App\Models\Order::find($order_id->id);
            $status = $order->delivery_status;
            if ($order->payment_status == 'paid'){
                $orderStatus =  translate('Paid');
            }else{
                $orderStatus =  translate('Unpaid');
            }
            $data->push([
                'Sno.' => $key + 1,
                'Order Code' => $order->code,
                'Number of Product Sale' => count($order->orderDetails->where('seller_id', Auth::user()->id)),
                'Customer' => optional($order->user)->name,
                'Amount' => single_price($order->grand_total),
                'Delivery Status' => translate(ucfirst(str_replace('_', ' ', $status))),
                'Payment Status' => $orderStatus
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Sno.',
            'Order Code',
            'Number of Product Sale' ,
            'Customer' ,
            'Amount',
            'Delivery Status' ,
            'Payment Status'
        ];
    }
}
