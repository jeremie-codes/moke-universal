<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CustomerAccountController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);
        $previousSessionId = $request->session()->getId();

        if (! Auth::attempt($validated)) {
            throw ValidationException::withMessages([
                'login' => 'Adresse e-mail ou mot de passe incorrect.',
            ]);
        }

        $request->session()->regenerate();
        $this->claimCart($previousSessionId, $request->session()->getId(), $request->user());

        return redirect()->route('checkout.index')->with('status', 'Vous êtes connecté. Vous pourrez suivre vos commandes depuis votre compte.');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $previousSessionId = $request->session()->getId();
        $user = User::create($validated);

        Auth::login($user);
        $request->session()->regenerate();
        $this->claimCart($previousSessionId, $request->session()->getId(), $user);

        return redirect()->route('checkout.index')->with('status', 'Votre compte est créé. Vous pourrez suivre vos commandes depuis votre compte.');
    }

    public function logout(Request $request)
    {
        $userId = $request->user()->id;

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Cart::query()
            ->where('user_id', $userId)
            ->update([
                'user_id' => null,
                'session_id' => $request->session()->getId(),
            ]);

        return redirect()->route('checkout.index');
    }

    public function orders(Request $request)
    {
        $orders = $request->user()
            ->orders()
            ->with('items.product', 'items.deliveryType', 'items.order')
            ->latest()
            ->paginate(10);

        return view('cart.orders', compact('orders'));
    }

    public function account(Request $request)
    {
        return view('cart.account', ['user' => $request->user()]);
    }

    public function updateAccount(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:40'],
            'address' => ['nullable', 'string', 'max:2000'],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? null;
        $user->address = $validated['address'] ?? null;

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return redirect()->route('customer.account')->with('status', 'Vos informations ont été mises à jour.');
    }

    private function claimCart(string $previousSessionId, string $currentSessionId, User $user): void
    {
        DB::transaction(function () use ($previousSessionId, $currentSessionId, $user) {
            $sessionCart = Cart::query()
                ->where('session_id', $previousSessionId)
                ->first();
            $userCart = Cart::query()
                ->where('user_id', $user->id)
                ->first();

            if (! $sessionCart) {
                $userCart?->update(['session_id' => $currentSessionId]);

                return;
            }

            if (! $userCart || $userCart->is($sessionCart)) {
                $sessionCart->update([
                    'user_id' => $user->id,
                    'session_id' => $currentSessionId,
                ]);

                return;
            }

            foreach ($sessionCart->items as $item) {
                $existingItem = $this->matchingCartItem($userCart, $item);

                if ($existingItem) {
                    $existingItem->increment('quantity', $item->quantity);
                    $item->delete();
                } else {
                    $item->update(['cart_id' => $userCart->id]);
                }
            }

            $sessionCart->delete();
            $userCart->update(['session_id' => $currentSessionId]);
        });
    }

    private function matchingCartItem(Cart $cart, CartItem $item): ?CartItem
    {
        $query = $cart->items()->where('product_id', $item->product_id);

        foreach (['variant_id', 'product_option_id', 'variant_option_id', 'delivery_type_id'] as $column) {
            $item->{$column} === null
                ? $query->whereNull($column)
                : $query->where($column, $item->{$column});
        }

        return $query->first();
    }
}
