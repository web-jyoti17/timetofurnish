<?php

namespace App\Http\Controllers\Seller;

use App\Models\Order;
use App\Models\Product;
use Auth;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    // public function index()
    // {
    //       $sellerId = Auth::user()->id;

    //     $data['products'] = filter_products(Product::where('user_id', Auth::user()->id)->orderBy('num_of_sale', 'desc'))->limit(12)->get();
    //     $data['last_7_days_sales'] = Order::where('created_at', '>=', Carbon::now()->subDays(7))
    //                             ->where('seller_id', '=', Auth::user()->id)
    //                             ->where('delivery_status', '=', 'delivered')
    //                             ->select(DB::raw("sum(grand_total) as total, DATE_FORMAT(created_at, '%d %b') as date"))
    //                             ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
    //                             ->get()->pluck('total', 'date');  

    //     return view('seller.dashboard', $data);
    // }
    // shivani
    public function index()
{
    $sellerId = Auth::user()->id;

    // Top 12 products of the seller
    $data['products'] = filter_products(
        Product::where('user_id', $sellerId)
            ->orderBy('num_of_sale', 'desc')
    )->limit(12)->get();

    // Last 7 days sales
    $data['last_7_days_sales'] = Order::where('created_at', '>=', Carbon::now()->subDays(7))
        ->where('seller_id', $sellerId)
        ->where('delivery_status', 'delivered')
        ->select(DB::raw("sum(grand_total) as total, DATE_FORMAT(created_at, '%d %b') as date"))
        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
        ->get()->pluck('total', 'date');  

    // Total delivered orders
    $data['total_delivered_orders'] = Order::where('seller_id', $sellerId)
        ->where('delivery_status', 'delivered')
        ->count();

    // Latest 5 delivered orders
    $data['latest_orders'] = Order::where('seller_id', $sellerId)
        ->where('delivery_status', 'delivered')
        ->orderBy('id', 'desc')
        ->take(5)
        ->get();

    return view('seller.dashboard', $data);
}

}
