<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;

class ShippingChargeController extends Controller
{
    public function index()
    {
        $shippingCharges = ShippingCharge::with('categories')->orderBy('sort_order')->get();
        $categories = $this->categories();

        return view('backend.shippingCharges.index', compact('shippingCharges', 'categories'));
    }

    public function store(Request $request)
    {
        $shippingCharge = ShippingCharge::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'status' => $request->has('status') ? 1 : 0,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        $shippingCharge->categories()->sync($request->categories ?? []);

        return redirect()->route('shipping-charges.index')->with('success', translate('Shipping charge created successfully'));
    }

    public function edit($id)
    {
        $shippingCharge = ShippingCharge::with('categories')->findOrFail($id);
        $shippingCharges = ShippingCharge::with('categories')->orderBy('sort_order')->get();
        $categories = $this->categories();

        return view('backend.shippingCharges.index', compact('shippingCharge', 'shippingCharges', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $shippingCharge = ShippingCharge::findOrFail($id);

        $shippingCharge->update([
            'name' => $request->name ?? $shippingCharge->name,
            'price' => $request->price ?? $shippingCharge->price,
            'description' => $request->description ?? $shippingCharge->description,
            'status' => $request->has('status') ? (int) $request->status : 0,
            'sort_order' => $request->sort_order ?? $shippingCharge->sort_order,
        ]);

        if ($request->has('categories')) {
            $shippingCharge->categories()->sync($request->categories ?? []);
        }

        if ($request->ajax()) {
            return 1;
        }

        return redirect()->route('shipping-charges.index')->with('success', translate('Shipping charge updated successfully'));
    }

    public function destroy($id)
    {
        $shippingCharge = ShippingCharge::findOrFail($id);
        $shippingCharge->categories()->detach();
        $shippingCharge->delete();

        return redirect()->route('shipping-charges.index')->with('success', translate('Shipping charge deleted successfully'));
    }

    private function categories()
    {
        return Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();
    }
}
