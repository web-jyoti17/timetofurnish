<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\Product;
use Auth;

class CouponController extends Controller
{
    /**
     * Seller Coupons List
     */
    public function index()
    {
        $coupons = Coupon::where('user_id', Auth::id())
                        ->orderBy('id', 'desc')
                        ->get();

        return view('seller.coupons.index', compact('coupons'));
    }

    /**
     * Show Create Coupon Page
     */
    public function create()
    {
        return view('seller.coupons.create');
    }

    /**
     * Store New Coupon
     */
    public function store(CouponRequest $request)
    {
        Coupon::create($request->validated() + [
            'user_id' => Auth::id(),
        ]);

        flash(translate('Coupon has been saved successfully.'))->success();
        return redirect()->route('seller.coupon.index');
    }

    /**
     * Edit Coupon Page
     */
    public function edit($id)
    {
        $coupon = Coupon::findOrFail(decrypt($id));
        return view('seller.coupons.edit', compact('coupon'));
    }

    /**
     * Update Coupon
     */
    // public function update(CouponRequest $request, Coupon $coupon)
    // {
    //     $coupon->update($request->validated());

    //     flash(translate('Coupon has been updated successfully'))->success();
    //     return redirect()->route('seller.coupon.index');
    // }

public function update(CouponRequest $request, $id)
{
    try {
        $coupon = Coupon::findOrFail(decrypt($id));
    } catch (\Exception $e) {
        flash(translate('Invalid Coupon ID'))->error();
        return redirect()->route('seller.coupon.index');
    }

    $coupon->update($request->validated());

    flash(translate('Coupon has been updated successfully'))->success();
    return redirect()->route('seller.coupon.index');
}

/**
     * Delete Coupon
     */
    public function destroy($id)
    {
        Coupon::destroy($id);

        flash(translate('Coupon has been deleted successfully'))->success();
        return redirect()->route('seller.coupon.index');
    }

    /**
     * AJAX — Coupon Form (Product Base OR Cart Base)
     */
    public function get_coupon_form(Request $request)
    {
        if ($request->coupon_type == "product_base") {

            // FIXED: Showing all seller products
            $products = Product::where('user_id', Auth::id())
                                ->where('digital', 0)
                                ->where('auction_product', 0)
                                ->where('wholesale_product', 0)
                                ->orderBy('created_at', 'desc')
                                ->get();

            return view('partials.coupons.product_base_coupon', compact('products'));
        }

        if ($request->coupon_type == "cart_base") {
            return view('partials.coupons.cart_base_coupon');
        }
    }

    /**
     * AJAX — Edit Coupon Form (Product Base / Cart Base)
     */
    public function get_coupon_form_edit(Request $request)
    {
        $coupon = Coupon::findOrFail($request->id);

        if ($request->coupon_type == "product_base") {

            // FIXED: Showing all seller products
            $products = Product::where('user_id', Auth::id())
                                ->where('digital', 0)
                                ->where('auction_product', 0)
                                ->where('wholesale_product', 0)
                                ->orderBy('created_at', 'desc')
                                ->get();

            return view('partials.coupons.product_base_coupon_edit', compact('coupon', 'products'));
        }

        if ($request->coupon_type == "cart_base") {
            return view('partials.coupons.cart_base_coupon_edit', compact('coupon'));
        }
    }
}
