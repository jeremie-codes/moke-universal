<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->currentCart(request());
        $cartItems = $cart?->items()
            ->with([
                'product.category',
                'product.media',
                'product.deliveryTypes',
                'variant',
                'productOption',
                'variantOption',
                'deliveryType',
            ])
            ->get() ?? collect();
        $subtotal = $cartItems->sum(fn (CartItem $item) => (float) $item->price * $item->quantity);

        return view('cart.index', compact('cartItems', 'subtotal'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'variant_id' => ['nullable', 'integer'],
            'product_option_id' => ['nullable', 'integer'],
            'variant_option_id' => ['nullable', 'integer'],
            'delivery_type_id' => ['nullable', 'integer'],
            'quantity' => ['sometimes', 'integer', 'min:1'],
        ]);

        return DB::transaction(function () use ($request, $validated) {
            $product = Product::query()
                ->whereKey($validated['product_id'])
                ->where('is_active', true)
                ->lockForUpdate()
                ->firstOrFail();
            $product->load(['options', 'variants.options', 'deliveryTypes']);
            $variant = isset($validated['variant_id'])
                ? $product->variants->firstWhere('id', (int) $validated['variant_id'])
                : null;
            $productOptions = $product->options->whereNull('variant_id');
            $variantOptions = $variant?->options ?? collect();
            $productOption = isset($validated['product_option_id'])
                ? $productOptions->firstWhere('id', (int) $validated['product_option_id'])
                : null;
            $variantOption = isset($validated['variant_option_id'])
                ? $variantOptions->firstWhere('id', (int) $validated['variant_option_id'])
                : null;

            if ($product->variants->isNotEmpty() && !$variant) {
                throw ValidationException::withMessages([
                    'variant_id' => 'Veuillez choisir une variante valide.',
                ]);
            }

            if (isset($validated['variant_id']) && !$variant) {
                throw ValidationException::withMessages([
                    'variant_id' => 'La variante sélectionnée ne correspond pas à ce produit.',
                ]);
            }

            if ($productOptions->isNotEmpty() && !$productOption) {
                throw ValidationException::withMessages([
                    'product_option_id' => 'Veuillez choisir une option valide pour ce produit.',
                ]);
            }

            if (isset($validated['product_option_id']) && !$productOption) {
                throw ValidationException::withMessages([
                    'product_option_id' => 'L’option sélectionnée ne correspond pas à ce produit.',
                ]);
            }

            if ($variantOptions->isNotEmpty() && !$variantOption) {
                throw ValidationException::withMessages([
                    'variant_option_id' => 'Veuillez choisir une option pour cette variante.',
                ]);
            }

            if (isset($validated['variant_option_id']) && !$variantOption) {
                throw ValidationException::withMessages([
                    'variant_option_id' => 'L’option sélectionnée ne correspond pas à cette variante.',
                ]);
            }

            $deliveryType = isset($validated['delivery_type_id'])
                ? $product->deliveryTypes->firstWhere('id', (int) $validated['delivery_type_id'])
                : null;

            if ($product->deliveryTypes->isNotEmpty() && !$deliveryType) {
                throw ValidationException::withMessages([
                    'delivery_type_id' => 'Veuillez choisir un mode de réception autorisé pour ce produit.',
                ]);
            }

            if (isset($validated['delivery_type_id']) && !$deliveryType) {
                throw ValidationException::withMessages([
                    'delivery_type_id' => 'Ce mode de réception n’est pas autorisé pour ce produit.',
                ]);
            }

            $selectedOptions = collect([$productOption, $variantOption])->filter();
            $stock = $selectedOptions->isNotEmpty()
                ? (int) $selectedOptions->min('stock')
                : (int) $product->stock;
            $cart = $this->currentCart($request);
            $item = $cart?->items()
                ->where('product_id', $product->id)
                ->where('variant_id', $variant?->id)
                ->where('product_option_id', $productOption?->id)
                ->where('variant_option_id', $variantOption?->id)
                ->where('delivery_type_id', $deliveryType?->id)
                ->lockForUpdate()
                ->first();
            $quantity = (int) ($validated['quantity'] ?? 1);
            $newQuantity = ($item?->quantity ?? 0) + $quantity;

            if ($newQuantity > $stock) {
                return response()->json(['message' => 'La quantité demandée dépasse le stock disponible.'], 422);
            }

            $cart ??= Cart::create([
                'user_id' => $request->user()?->id,
                'session_id' => $request->session()->getId(),
            ]);

            if ($item) {
                $item->increment('quantity', $quantity);
            } else {
                $item = $cart->items()->create([
                    'product_id' => $product->id,
                    'variant_id' => $variant?->id,
                    'product_option_id' => $productOption?->id,
                    'variant_option_id' => $variantOption?->id,
                    'delivery_type_id' => $deliveryType?->id,
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
            'quantity' => ['sometimes', 'required', 'integer', 'min:1'],
            'delivery_type_id' => ['sometimes', 'nullable', 'integer'],
        ]);
        if ($validated === []) {
            throw ValidationException::withMessages([
                'cart_item' => 'Aucune modification à enregistrer.',
            ]);
        }

        $cart = $this->currentCart($request);
        abort_if(!$cart, 404);
        $cartItem = $cart->items()
            ->with(['product.deliveryTypes', 'productOption', 'variantOption'])
            ->whereKey($item)
            ->firstOrFail();

        if (isset($validated['quantity']) && $validated['quantity'] > $cartItem->availableStock()) {
            return response()->json(['message' => 'La quantité demandée dépasse le stock disponible.'], 422);
        }

        $updates = [];
        if (isset($validated['quantity'])) {
            $updates['quantity'] = $validated['quantity'];
        }

        if (array_key_exists('delivery_type_id', $validated)) {
            $deliveryType = isset($validated['delivery_type_id'])
                ? $cartItem->product->deliveryTypes->firstWhere('id', (int) $validated['delivery_type_id'])
                : null;
            $allowedDeliveryTypes = $cartItem->product->deliveryTypes;

            if (($allowedDeliveryTypes->isNotEmpty() && !$deliveryType)
                || (isset($validated['delivery_type_id']) && !$deliveryType)) {
                throw ValidationException::withMessages([
                    'delivery_type_id' => 'Choisissez un mode de réception autorisé pour ce produit.',
                ]);
            }

            $updates['delivery_type_id'] = $deliveryType?->id;
        }

        $cartItem->update($updates);

        return response()->json($this->cartSummary($cart) + [
            'item_id' => $cartItem->id,
            'quantity' => $cartItem->quantity,
            'delivery_type_id' => $cartItem->delivery_type_id,
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
            ->where(function ($query) use ($request) {
                $query->where('session_id', $request->session()->getId());
                if ($request->user()) {
                    $query->orWhere('user_id', $request->user()->id);
                }
            })
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
