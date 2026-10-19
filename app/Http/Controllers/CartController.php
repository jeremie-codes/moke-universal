<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->currentCart(request());
        $cartItems = $cart?->items()->with(['product.category', 'product.media'])->get() ?? collect();
        $subtotal = $cartItems->sum(fn (CartItem $item) => (float) $item->price * $item->quantity);

        return view('cart.index', compact('cartItems', 'subtotal'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['sometimes', 'integer', 'min:1'],
        ]);

        return DB::transaction(function () use ($request, $validated) {
            $product = Product::query()
                ->whereKey($validated['product_id'])
                ->where('is_active', true)
                ->lockForUpdate()
                ->firstOrFail();
            $cart = $this->currentCart($request);
            $item = $cart?->items()
                ->where('product_id', $product->id)
                ->lockForUpdate()
                ->first();
            $quantity = (int) ($validated['quantity'] ?? 1);
            $newQuantity = ($item?->quantity ?? 0) + $quantity;

            if ($newQuantity > $product->stock) {
                return response()->json(['message' => 'La quantité demandée dépasse le stock disponible.'], 422);
            }

            $cart ??= Cart::create(['session_id' => $request->session()->getId()]);

            if ($item) {
                $item->increment('quantity', $quantity);
            } else {
                $item = $cart->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);
            }

            return response()->json($this->cartSummary($cart) + ['item_id' => $item->id]);
        });
    }

    public function update(Request $request, int $item)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);
        $cart = $this->currentCart($request);
        abort_if(!$cart, 404);
        $cartItem = $cart->items()->with('product')->whereKey($item)->firstOrFail();

        if ($validated['quantity'] > $cartItem->product->stock) {
            return response()->json(['message' => 'La quantité demandée dépasse le stock disponible.'], 422);
        }

        $cartItem->update(['quantity' => $validated['quantity']]);

        return response()->json($this->cartSummary($cart) + [
            'item_id' => $cartItem->id,
            'quantity' => $cartItem->quantity,
            'line_subtotal' => round((float) $cartItem->price * $cartItem->quantity, 2),
        ]);
    }

    public function destroy(Request $request, int $item)
    {
        $cart = $this->currentCart($request);
        abort_if(!$cart, 404);
        $cart->items()->whereKey($item)->firstOrFail()->delete();

        return response()->json($this->cartSummary($cart));
    }

    private function currentCart(Request $request): ?Cart
    {
        return Cart::query()
            ->where('session_id', $request->session()->getId())
            ->first();
    }

    private function cartSummary(Cart $cart): array
    {
        $items = $cart->items()->get(['quantity', 'price']);

        return [
            'cart_count' => (int) $items->sum('quantity'),
            'subtotal' => round($items->sum(fn (CartItem $item) => (float) $item->price * $item->quantity), 2),
        ];
    }
}
