<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Models\CommissionHistory;
use Auth;
use Carbon\Carbon;

class CommissionHistoryController extends Controller
{
    public function index(Request $request) {
        $date_range = null;
        
        // Eager load user and order
        $commission_history = CommissionHistory::with(['user', 'order'])
            ->where('seller_id', Auth::user()->id)
            ->orderBy('created_at', 'desc');
        
        if ($request->date_range) {
            $date_range = $request->date_range;
            $dates = explode(" / ", $request->date_range);

            $start_date = Carbon::parse($dates[0])->startOfDay();
            $end_date   = Carbon::parse($dates[1])->endOfDay();

            $commission_history = $commission_history
                ->where('created_at', '>=', $start_date)
                ->where('created_at', '<=', $end_date);
        }
        
        $commission_history = $commission_history->paginate(10);

        return view('seller.commission_history.index', compact('commission_history', 'date_range'));
    }
}
