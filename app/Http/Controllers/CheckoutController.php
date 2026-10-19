<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Services\FlexpaieService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cart = $this->currentCart($request);
        $cartItems = $cart?->items()->with('product')->get() ?? collect();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide.');
        }

        $subtotal = $cartItems->sum(fn ($item) => (float) $item->price * $item->quantity);

        return view('cart.checkout-page', compact('cartItems', 'subtotal'));
    }

    public function store(Request $request, FlexpaieService $flexpay)
    {
        $validated = $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'address' => ['required', 'string', 'max:2000'],
            'payment_method' => ['required', Rule::in(['mobile', 'card'])],
            'payment_phone' => ['nullable', 'string', 'max:40', 'required_if:payment_method,mobile'],
        ]);

        [$order, $payment] = DB::transaction(function () use ($request, $validated) {
            $cart = Cart::query()
                ->where('session_id', $request->session()->getId())
                ->with('items.product')
                ->lockForUpdate()
                ->first();

            if (!$cart || $cart->items->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => 'Votre panier est vide.',
                ]);
            }

            foreach ($cart->items as $item) {
                if (!$item->product || !$item->product->is_active || $item->quantity > $item->product->stock) {
                    throw ValidationException::withMessages([
                        'cart' => 'Un produit de votre panier n’est plus disponible dans la quantité demandée.',
                    ]);
                }
            }

            $subtotal = $cart->items->sum(fn ($item) => (float) $item->price * $item->quantity);
            $order = Order::create([
                'user_id' => $request->user()?->id,
                ...$validated,
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'status' => 'pending',
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                ]);
            }

            $payment = $order->payment()->create([
                'provider' => 'flexpay',
                'method' => $validated['payment_method'],
                'phone' => $validated['payment_phone'] ?? null,
                'amount' => $order->total,
                'currency' => 'CDF',
                'status' => 'pending',
            ]);

            return [$order, $payment];
        });

        try {
            $response = $validated['payment_method'] === 'mobile'
                ? $flexpay->mobilePayment(
                    $payment->reference,
                    $order->total,
                    $validated['payment_phone'],
                    'CDF',
                    route('checkout.payment.callback', ['reference' => $payment->reference]),
                )
                : $flexpay->cardPayment(
                    $payment->reference,
                    $order->total,
                    'CDF',
                    route('checkout.payment.callback', ['reference' => $payment->reference]),
                    route('checkout.payment.success', ['reference' => $payment->reference]),
                    route('checkout.payment.cancel', ['reference' => $payment->reference]),
                    route('checkout.payment.decline', ['reference' => $payment->reference]),
                );
        } catch (\Throwable $exception) {
            Log::error('FlexPay initialization error', [
                'payment_reference' => $payment->reference,
                'message' => $exception->getMessage(),
            ]);

            $this->failPayment($payment, ['message' => 'Impossible de joindre le service de paiement.']);

            throw ValidationException::withMessages([
                'payment' => 'Le paiement n’a pas pu être initialisé. Réessayez dans quelques instants.',
            ]);
        }

        if ((int) ($response['code'] ?? 1) !== FlexpaieService::SUCCESS || empty($response['orderNumber'])) {
            $this->failPayment($payment, $response);

            throw ValidationException::withMessages([
                'payment' => $response['message'] ?? 'Le paiement a été refusé par FlexPay.',
            ]);
        }

        DB::transaction(function () use ($request, $payment, $response) {
            $payment->update([
                'transaction_id' => $response['orderNumber'],
                'response' => $response,
            ]);

            Cart::query()
                ->where('session_id', $request->session()->getId())
                ->first()
                ?->items()
                ->delete();
        });

        if ($validated['payment_method'] === 'card' && !empty($response['url'])) {
            return redirect()->away($response['url']);
        }

        return redirect()->route('checkout.payment.status', $payment->reference);
    }

    public function success(string $reference)
    {
        $order = Order::query()
            ->where('reference', $reference)
            ->with('items.product')
            ->firstOrFail();

        return view('cart.checkout-success', compact('order'));
    }

    public function paymentStatusPage(string $reference)
    {
        $payment = Payment::query()
            ->with('order')
            ->where('reference', $reference)
            ->firstOrFail();

        return view('cart.payment-status', compact('payment'));
    }

    public function checkPaymentStatus(string $reference, FlexpaieService $flexpay)
    {
        $payment = Payment::query()->where('reference', $reference)->firstOrFail();

        if ($payment->status === 'pending' && $payment->transaction_id) {
            try {
                $response = $flexpay->getPaymentStatus($payment->transaction_id);
                $providerStatus = (int) data_get($response, 'transaction.status', 2);
                $payment = $this->synchronizePayment($payment, $providerStatus, $response);
            } catch (\Throwable $exception) {
                Log::warning('FlexPay status check error', [
                    'payment_reference' => $payment->reference,
                    'message' => $exception->getMessage(),
                ]);
            }
        }

        return response()->json($this->paymentStatusPayload($payment));
    }

    public function paymentCallback(Request $request, string $reference)
    {
        $response = $request->json()->all() ?: $request->all();
        $providerStatus = (int) data_get($response, 'status', data_get($response, 'transaction.status', 2));
        $payment = Payment::query()->where('reference', $reference)->firstOrFail();

        $this->synchronizePayment($payment, $providerStatus, $response);

        return response()->json(['success' => true]);
    }

    public function paymentSuccess(string $reference)
    {
        $payment = Payment::query()->where('reference', $reference)->firstOrFail();
        $this->synchronizePayment($payment, FlexpaieService::SUCCESS, ['transaction' => ['status' => 0]]);

        return redirect()->route('checkout.payment.status', $reference);
    }

    public function paymentCancel(string $reference)
    {
        $payment = Payment::query()->where('reference', $reference)->firstOrFail();
        $this->synchronizePayment($payment, 4, ['transaction' => ['status' => 4]]);

        return redirect()->route('checkout.payment.status', $reference);
    }

    public function paymentDecline(string $reference)
    {
        $payment = Payment::query()->where('reference', $reference)->firstOrFail();
        $this->synchronizePayment($payment, 1, ['transaction' => ['status' => 1]]);

        return redirect()->route('checkout.payment.status', $reference);
    }

    private function synchronizePayment(Payment $payment, int $providerStatus, array $response): Payment
    {
        return DB::transaction(function () use ($payment, $providerStatus, $response) {
            $payment = Payment::query()->lockForUpdate()->findOrFail($payment->id);

            if ($payment->status !== 'pending') {
                return $payment;
            }

            $attributes = ['response' => $response];

            if ($providerStatus === FlexpaieService::SUCCESS) {
                $attributes['status'] = 'success';
                $attributes['paid_at'] = now();
                $payment->order()->update(['status' => 'paid']);
            } elseif (in_array($providerStatus, [1, 4], true)) {
                $attributes['status'] = 'failed';
                $payment->order()->update(['status' => $providerStatus === 4 ? 'cancelled' : 'failed']);
            }

            $payment->update($attributes);

            return $payment->fresh();
        });
    }

    private function failPayment(Payment $payment, array $response): void
    {
        $this->synchronizePayment($payment, 1, $response);
    }

    private function paymentStatusPayload(Payment $payment): array
    {
        $providerStatus = (int) data_get($payment->response, 'transaction.status', 2);
        $status = match ($payment->status) {
            'success' => 'success',
            'failed' => $providerStatus === 4 ? 'cancelled' : 'failed',
            default => 'pending',
        };

        return [
            'status' => $status,
            'message' => match ($status) {
                'success' => 'Votre paiement a été confirmé.',
                'cancelled' => 'Le paiement a été annulé.',
                'failed' => 'Le paiement a été refusé ou a échoué.',
                default => 'En attente de confirmation du paiement.',
            },
        ];
    }

    private function currentCart(Request $request): ?Cart
    {
        return Cart::query()
            ->where('session_id', $request->session()->getId())
            ->first();
    }
}
