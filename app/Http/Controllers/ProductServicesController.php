<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CheckoutService;
use App\Models\Category;

class ProductServicesController extends Controller
{
    public function index()
    {
        $services = CheckoutService::with('categories')->get();

        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->get();

        return view(
            'backend.productServices.index',
            compact('services', 'categories')
        );
    }

    public function store(Request $request)
    {
        $service = CheckoutService::create([

            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
            'description' => $request->description,
            'status' => $request->status,
            'sort_order' => $request->sort_order,
        ]);

        if ($request->categories) {

            $service->categories()->sync($request->categories);
        }

        return redirect()
            ->route('services.index')
            ->with('success', 'Service created successfully');
    }

    public function edit($id)
{
    $service = CheckoutService::with('categories')->findOrFail($id);

    $services = CheckoutService::with('categories')->get();

    $categories = Category::where('parent_id', 0)
        ->where('digital', 0)
        ->get();

    return view(
        'backend.productServices.index',
        compact('service', 'services', 'categories')
    );
}

    public function update(Request $request, $id)
    {
        $service = CheckoutService::findOrFail($id);

        $service->update([

            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
            'description' => $request->description,
            'status' => $request->status,
            'sort_order' => $request->sort_order,
        ]);

        if ($request->categories) {

            $service->categories()->sync($request->categories);
        }

        return redirect()
            ->route('services.index')
            ->with('success', 'Service updated successfully');
    }

    public function destroy($id)
    {
        $service = CheckoutService::with('categories')->findOrFail($id);
        $service->categories()->detach();
        $service->delete();

    return redirect()->route('services.index')->with('success', translate('Service deleted successfully'));
    }
}
