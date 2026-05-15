<?php
namespace App\Exports;

use App\Models\OrderDetail;
use App\Models\Shop;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SellerSaleReportExport implements FromCollection, WithHeadings
{
    protected $shop_id;

    public function __construct($shop_id = null)
    {
        $this->shop_id = $shop_id;
    }

    public function collection()
    {
        $data = collect([]);

        $query = Shop::with('user');

        if ($this->shop_id) {
            $query->where('id', $this->shop_id);
        }

        $shops = $query->orderBy('created_at', 'desc')->get();

        foreach ($shops as $shop) {

            if (!$shop->user) continue;

            $num_of_sale = Product::where('user_id', $shop->user_id)
                ->sum('num_of_sale');

            $orderAmount = OrderDetail::where('seller_id', $shop->user_id)
                ->sum('price');

            $data->push([
                'Seller Name' => $shop->user->name ?? '--',
                'Shop Name' => $shop->name ?? '--',
                'Number of Product Sale' => $num_of_sale,
                'Order Amount' => $orderAmount,
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Seller Name',
            'Shop Name',
            'Number of Product Sale',
            'Order Amount'
        ];
    }
}