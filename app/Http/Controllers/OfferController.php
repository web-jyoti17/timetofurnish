<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offer;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $offersQuery = Offer::with(['user', 'products'])->orderBy('priority', 'desc')->orderBy('id', 'desc');

        if ($search) {
            $offersQuery->where('name', 'like', "%{$search}%");
        }

        $offers = $offersQuery->paginate(15);

        return view('backend.marketing.offers.index', compact('offers', 'search'));
    }

    public function sellerAutoApproval(Request $request)
    {
        $search = $request->search;
        $sellersQuery = User::whereIn('user_type', ['seller'])->orderBy('id', 'desc');

        if ($search) {
            $sellersQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $sellers = $sellersQuery->paginate(15);

        return view('backend.marketing.offers.seller_auto_approval', compact('sellers', 'search'));
    }

    public function create()
    {
        $products = Product::where('published', 1)->get();
        return view('backend.marketing.offers.create', compact('products'));
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
        ];

        if ($request->discount_type === 'percentage') {
            $rules['discount_value'] = 'required|numeric|min:0|max:99.99';
        } elseif ($request->discount_type === 'fixed') {
            $rules['discount_value'] = 'required|numeric|min:0';
        }

        $request->validate($rules);

        // Active Offer Validation: Ensure none of the chosen products are already in another active/pending offer
        $busy_product_ids = Offer::getBusyProductIds();
        $duplicate_products = array_intersect($request->products, $busy_product_ids);
        if (!empty($duplicate_products)) {
            $product_names = Product::whereIn('id', $duplicate_products)->pluck('name')->toArray();
            return back()->withInput()->withErrors([
                'products' => translate('The following products already have an active offer: ') . implode(', ', $product_names)
            ]);
        }

        $offer = new Offer();
        $offer->user_id = null; // Admin-created
        $offer->name = $request->name;
        $offer->badge_text = $request->badge_text;
        $offer->discount_type = $request->discount_type;
        $offer->discount_value = $request->discount_type !== 'badge_only' ? $request->discount_value : null;
        $offer->custom_text = $request->custom_text;
        
        $offset = (int)$request->input('timezone_offset', 0);
        $offer->starts_at = $request->starts_at ? Carbon::parse($request->starts_at)->addMinutes($offset) : null;
        $offer->ends_at = $request->ends_at ? Carbon::parse($request->ends_at)->addMinutes($offset) : null;
        
        $offer->status = 'approved'; // Auto-approved because created by admin
        $offer->priority = $request->priority ?? 0;
        $offer->show_on_home = $request->show_on_home ? 1 : 0;
        $offer->save();

        $offer->products()->sync($request->products);

        flash(translate('Offer has been created successfully'))->success();
        return redirect()->route('offers.index');
    }

    public function edit($id)
    {
        $offer = Offer::findOrFail($id);
        $products = Product::where('published', 1)->get();
        return view('backend.marketing.offers.edit', compact('offer', 'products'));
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
        ];

        if ($request->discount_type === 'percentage') {
            $rules['discount_value'] = 'required|numeric|min:0|max:99.99';
        } elseif ($request->discount_type === 'fixed') {
            $rules['discount_value'] = 'required|numeric|min:0';
        }

        $request->validate($rules);

        // Active Offer Validation: Ensure none of the chosen products are already in another active/pending offer
        $busy_product_ids = Offer::getBusyProductIds($id);
        $duplicate_products = array_intersect($request->products, $busy_product_ids);
        if (!empty($duplicate_products)) {
            $product_names = Product::whereIn('id', $duplicate_products)->pluck('name')->toArray();
            return back()->withInput()->withErrors([
                'products' => translate('The following products already have an active offer: ') . implode(', ', $product_names)
            ]);
        }

        $offer = Offer::findOrFail($id);
        $offer->name = $request->name;
        $offer->badge_text = $request->badge_text;
        $offer->discount_type = $request->discount_type;
        $offer->discount_value = $request->discount_type !== 'badge_only' ? $request->discount_value : null;
        $offer->custom_text = $request->custom_text;
        
        $offset = (int)$request->input('timezone_offset', 0);
        $offer->starts_at = $request->starts_at ? Carbon::parse($request->starts_at)->addMinutes($offset) : null;
        $offer->ends_at = $request->ends_at ? Carbon::parse($request->ends_at)->addMinutes($offset) : null;
        
        $offer->priority = $request->priority ?? 0;
        $offer->show_on_home = $request->show_on_home ? 1 : 0;
        $offer->save();

        $offer->products()->sync($request->products);

        flash(translate('Offer has been updated successfully'))->success();
        return redirect()->route('offers.index');
    }

    public function destroy($id)
    {
        $offer = Offer::findOrFail($id);
        $offer->products()->detach();
        $offer->delete();

        flash(translate('Offer has been deleted successfully'))->success();
        return redirect()->route('offers.index');
    }

    public function updateStatus(Request $request)
    {
        $offer = Offer::findOrFail($request->id);
        $offer->status = $request->status; // approved, rejected, inactive
        $offer->save();

        return response()->json(['success' => true, 'message' => translate('Status updated successfully')]);
    }

    public function updateHomeToggle(Request $request)
    {
        $offer = Offer::findOrFail($request->id);
        $offer->show_on_home = $request->show_on_home ? 1 : 0;
        $offer->save();

        return response()->json(['success' => true, 'message' => translate('Homepage display status toggled')]);
    }

    public function toggleAutoApprove(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->auto_approve_offers = $request->auto_approve_offers ? 1 : 0;
        $user->save();

        return response()->json(['success' => true, 'message' => translate('Auto approval status updated')]);
    }
}
