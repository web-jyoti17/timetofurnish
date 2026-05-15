<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function index(Request $request)
{
    $payments = Payment::where('seller_id', Auth::user()->id);

    if ($request->filled('date_range')) {

        $range = $request->date_range;

        // Handle both: "to" and "-"
        if (str_contains($range, ' to ')) {
            $dates = explode(' to ', $range);
        } elseif (str_contains($range, ' / ')) {
            $dates = explode(' /', $range);
        } else {
            $dates = [];
        }

        if (count($dates) === 2) {
            try {
                $startDate = Carbon::parse($dates[0])->startOfDay();
                $endDate   = Carbon::parse($dates[1])->endOfDay();

                $payments->whereBetween('created_at', [$startDate, $endDate]);
            } catch (\Exception $e) {
                // silently fail (no crash)
            }
        }
    }

    $payments = $payments->latest()->paginate(9);

    return view('seller.payment_history', [
        'payments' => $payments,
        'date_range' => $request->date_range
    ]);
}
}
