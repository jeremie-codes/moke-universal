<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::query()
            ->where('is_active', true)
            ->with(['category', 'media', 'options', 'variants.options', 'deliveryTypes'])
            ->latest()
            ->paginate(12);

        return view('shop.index', compact('products'));
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);
        $product->load(['category', 'media', 'options', 'variants.options', 'deliveryTypes']);

        return view('shop.show', compact('product'));
    }
}
