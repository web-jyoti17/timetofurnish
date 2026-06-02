<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Offer;
use App\Models\Product;
use Carbon\Carbon;
use Auth;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::where('user_id', Auth::id())
                       ->orderBy('priority', 'desc')
                       ->orderBy('id', 'desc')
                       ->paginate(15);

        return view('seller.offers.index', compact('offers'));
    }

    public function create()
    {
        // Sellers can only offer their own products
        $products = Product::where('user_id', Auth::id())
                           ->where('published', 1)
                           ->get();

        return view('seller.offers.create', compact('products'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'discount_type' => 'required|string|in:percentage,fixed,badge_only',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'products' => 'required|array',
            'priority' => 'nullable|integer|min:0',
            'template_style' => 'nullable|string|in:style_1,style_2,style_3',
        ];

        if ($request->discount_type === 'percentage') {
            $rules['discount_value'] = 'required|numeric|min:0|max:99.99';
        } elseif ($request->discount_type === 'fixed') {
            $rules['discount_value'] = 'required|numeric|min:0';
        }

        $request->validate($rules);

        // Sync products directly without duplicate check

        $offer = new Offer();
        $offer->user_id = Auth::id(); // Owned by this seller
        $offer->name = $request->name;
        $offer->badge_text = $request->badge_text;
        $offer->discount_type = $request->discount_type;
        $offer->discount_value = $request->discount_type !== 'badge_only' ? $request->discount_value : null;
        $offer->custom_text = $request->custom_text;
        
        $offset = (int)$request->input('timezone_offset', 0);
        $offer->starts_at = $request->starts_at ? Carbon::parse($request->starts_at)->addMinutes($offset) : null;
        $offer->ends_at = $request->ends_at ? Carbon::parse($request->ends_at)->addMinutes($offset) : null;
        
        // Auto-approve if the seller is trusted/auto-approved by admin
        if (Auth::user()->auto_approve_offers == 1) {
            $offer->status = 'approved';
        } else {
            $offer->status = 'pending';
        }

        $offer->priority = $request->priority ?? 0;
        $offer->show_on_home = 0; // Seller offers don't show on admin homepage section by default without admin approval
        $offer->template_style = $request->template_style ?? 'style_1';
        $offer->save();

        // Enforce security: Sync only products belonging to this seller
        $seller_products = Product::where('user_id', Auth::id())
                                  ->whereIn('id', $request->products)
                                  ->pluck('id')
                                  ->toArray();

        $offer->products()->sync($seller_products);

        if ($offer->status == 'approved') {
            flash(translate('Offer has been created and auto-approved successfully.'))->success();
        } else {
            flash(translate('Offer has been submitted and is pending Admin approval.'))->warning();
        }

        return redirect()->route('seller.offers.index');
    }

    public function edit($id)
    {
        $offer = Offer::where('user_id', Auth::id())->findOrFail($id);
        $products = Product::where('user_id', Auth::id())
                           ->where('published', 1)
                           ->get();

        return view('seller.offers.edit', compact('offer', 'products'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'discount_type' => 'required|string|in:percentage,fixed,badge_only',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'products' => 'required|array',
            'priority' => 'nullable|integer|min:0',
            'template_style' => 'nullable|string|in:style_1,style_2,style_3',
        ];

        if ($request->discount_type === 'percentage') {
            $rules['discount_value'] = 'required|numeric|min:0|max:99.99';
        } elseif ($request->discount_type === 'fixed') {
            $rules['discount_value'] = 'required|numeric|min:0';
        }

        $request->validate($rules);

        // Sync products directly without duplicate check

        $offer = Offer::where('user_id', Auth::id())->findOrFail($id);
        $offer->name = $request->name;
        $offer->badge_text = $request->badge_text;
        $offer->discount_type = $request->discount_type;
        $offer->discount_value = $request->discount_type !== 'badge_only' ? $request->discount_value : null;
        $offer->custom_text = $request->custom_text;
        
        $offset = (int)$request->input('timezone_offset', 0);
        $offer->starts_at = $request->starts_at ? Carbon::parse($request->starts_at)->addMinutes($offset) : null;
        $offer->ends_at = $request->ends_at ? Carbon::parse($request->ends_at)->addMinutes($offset) : null;
        
        $offer->priority = $request->priority ?? 0;
        $offer->template_style = $request->template_style ?? 'style_1';
        
        // Auto-approve if trusted, otherwise if edited, reset status to pending for admin re-verification
        if (Auth::user()->auto_approve_offers == 1) {
            $offer->status = 'approved';
        } else {
            $offer->status = 'pending';
        }

        $offer->save();

        // Security check
        $seller_products = Product::where('user_id', Auth::id())
                                  ->whereIn('id', $request->products)
                                  ->pluck('id')
                                  ->toArray();

        $offer->products()->sync($seller_products);

        if ($offer->status == 'approved') {
            flash(translate('Offer has been updated and auto-approved successfully.'))->success();
        } else {
            flash(translate('Offer has been updated and is pending Admin approval.'))->warning();
        }

        return redirect()->route('seller.offers.index');
    }

    public function destroy($id)
    {
        $offer = Offer::where('user_id', Auth::id())->findOrFail($id);
        $offer->products()->detach();
        $offer->delete();

        flash(translate('Offer has been deleted successfully.'))->success();
        return redirect()->route('seller.offers.index');
    }

    public function updateStatus(Request $request)
    {
        $offer = Offer::where('user_id', Auth::id())->findOrFail($request->id);
        
        if ($request->status === 'approved') {
            if (Auth::user()->auto_approve_offers == 1) {
                $offer->status = 'approved';
            } else {
                $offer->status = 'pending';
            }
        } else {
            $offer->status = 'inactive';
        }
        
        $offer->save();
        
        return response()->json([
            'success' => true, 
            'message' => translate('Status updated successfully'),
            'status' => $offer->status
        ]);
    }
}
