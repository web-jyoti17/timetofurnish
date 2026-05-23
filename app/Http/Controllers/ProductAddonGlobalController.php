<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductAddonGlobal;
use App\Models\ProductAddonOptionGlobal;

class ProductAddonGlobalController extends Controller
{
    public function index()
    {
        $addons = ProductAddonGlobal::with('options')
                    ->orderBy('sort_order', 'asc')
                    ->get();

        return view('backend.addons.global', compact('addons'));
    }

    public function store(Request $request)
    {
        // DELETE REMOVED ADDONS
        if ($request->deleted_addons) {

            foreach ($request->deleted_addons as $id) {

                $addon = ProductAddonGlobal::find($id);

                if ($addon) {

                    ProductAddonOptionGlobal::where('product_addon_id', $addon->id)->delete();

                    $addon->delete();
                }
            }
        }

        $addons = $request->addons ?? [];

        foreach ($addons as $aIndex => $addon) {

            // CREATE / UPDATE ADDON
            $addonModel = ProductAddonGlobal::updateOrCreate(
                ['id' => $addon['id'] ?? null],
                [
                    'name' => $addon['name'] ?? '',
                    'sort_order' => $addon['sort_order'] ?? 0
                ]
            );

            $optionIds = [];

            if (!empty($addon['options'])) {

                foreach ($addon['options'] as $oIndex => $option) {

                    $imgPath = $option['old_img'] ?? null;

                    // IMAGE UPLOAD
                    if ($request->hasFile("addons.$aIndex.options.$oIndex.img")) {

                        $file = $request->file("addons.$aIndex.options.$oIndex.img");

                        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                        $file->move(public_path('addon'), $filename);

                        $imgPath = 'addon/' . $filename;
                    }

                    $optModel = ProductAddonOptionGlobal::updateOrCreate(
                        ['id' => $option['id'] ?? null],
                        [
                            'product_addon_id' => $addonModel->id,
                            'option_name' => $option['option_name'] ?? '',
                            'price' => $option['price'] ?? 0,
                            'quantity' => $option['quantity'] ?? 0,
                            'img' => $imgPath,
                            'sort_order' => $option['sort_order'] ?? 0
                        ]
                    );

                    $optionIds[] = $optModel->id;
                }
            }

            // DELETE REMOVED OPTIONS
            ProductAddonOptionGlobal::where('product_addon_id', $addonModel->id)
                ->whereNotIn('id', $optionIds)
                ->delete();
        }

        return back()->with('success', 'Saved Successfully');
    }
}
