<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Category;
use App\Models\DeliveryType;
use App\Models\Order;
use App\Models\Option;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withCredentials()
            ->withCookie(config('session.cookie'), str_repeat('a', 40));
    }

    public function test_checkout_creates_a_pending_order_and_clears_the_cart(): void
    {
        Http::fake([
            'https://backend.flexpay.cd/*' => Http::response([
                'code' => 0,
                'message' => 'Paiement initialisé',
                'orderNumber' => 'FLEXPAY-001',
            ]),
        ]);

        $category = Category::create(['name' => 'Test']);
        $product = Product::create([
            'category_id' => $category->id,
            'title' => 'Produit de test',
            'type' => 'book',
            'price' => '15.00',
            'stock' => 4,
            'is_active' => true,
        ]);

        $this->postJson(route('cart.items.store'), ['product_id' => $product->id])->assertOk();

        $this->post(route('checkout.store'), [
            'fullname' => 'Client Test',
            'email' => 'client@example.com',
            'phone' => '+33123456789',
            'address' => '10 rue du Test, Paris',
            'payment_method' => 'mobile',
            'payment_phone' => '243999999999',
        ])->assertRedirect();

        $order = Order::query()->with('items')->firstOrFail();

        $this->assertSame('pending', $order->status);
        $this->assertSame('15.00', $order->subtotal);
        $this->assertSame('15.00', $order->total);
        $this->assertSame(1, $order->items->count());
        $this->assertSame(0, CartItem::query()->count());
        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'provider' => 'flexpay',
            'method' => 'mobile',
            'phone' => '243999999999',
            'status' => 'pending',
            'transaction_id' => 'FLEXPAY-001',
        ]);
    }

    public function test_payment_status_check_marks_the_order_as_paid(): void
    {
        $order = Order::create([
            'fullname' => 'Client Test',
            'email' => 'client@example.com',
            'phone' => '+33123456789',
            'address' => '10 rue du Test, Paris',
            'subtotal' => '15.00',
            'total' => '15.00',
        ]);
        $payment = Payment::create([
            'order_id' => $order->id,
            'provider' => 'flexpay',
            'method' => 'mobile',
            'phone' => '243999999999',
            'amount' => '15.00',
            'currency' => 'USD',
            'status' => 'pending',
            'transaction_id' => 'FLEXPAY-001',
        ]);

        Http::fake([
            'https://apicheck.flexpaie.com/*' => Http::response([
                'transaction' => ['status' => 0],
            ]),
        ]);

        $this->getJson(route('checkout.payment.check', $payment->reference))
            ->assertOk()
            ->assertJson([
                'status' => 'success',
            ]);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'success',
        ]);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'paid',
        ]);
    }

    public function test_checkout_redirects_to_the_cart_when_it_is_empty(): void
    {
        $this->get(route('checkout.index'))
            ->assertRedirect(route('cart.index'))
            ->assertSessionHas('error');
    }

    public function test_checkout_preserves_the_selected_variant_and_option(): void
    {
        Http::fake([
            'https://backend.flexpay.cd/*' => Http::response([
                'code' => 0,
                'message' => 'Paiement initialisé',
                'orderNumber' => 'FLEXPAY-VARIANT-001',
            ]),
        ]);

        $category = Category::create(['name' => 'Test']);
        $product = Product::create([
            'category_id' => $category->id,
            'title' => 'T-shirt',
            'type' => 'clothing',
            'price' => '15.00',
            'stock' => 0,
            'is_active' => true,
        ]);
        $variant = Variant::create([
            'product_id' => $product->id,
            'name' => 'Rouge',
        ]);
        $option = Option::create([
            'variant_id' => $variant->id,
            'name' => 'M',
            'stock' => 3,
        ]);
        $deliveryType = DeliveryType::query()->where('type', 'home_delivery')->firstOrFail();
        $product->deliveryTypes()->attach($deliveryType);

        $this->postJson(route('cart.items.store'), [
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'variant_option_id' => $option->id,
            'delivery_type_id' => $deliveryType->id,
        ])->assertOk();

        $this->post(route('checkout.store'), [
            'fullname' => 'Client Test',
            'email' => 'client@example.com',
            'phone' => '+33123456789',
            'address' => '10 rue du Test, Paris',
            'payment_method' => 'mobile',
            'payment_phone' => '243999999999',
        ])->assertRedirect();

        $orderItem = Order::query()->firstOrFail()->items()->firstOrFail();
        $this->assertSame($variant->id, $orderItem->variant_id);
        $this->assertSame($option->id, $orderItem->variant_option_id);
        $this->assertSame('Rouge', $orderItem->variant_name);
        $this->assertSame('M', $orderItem->variant_option_name);
        $this->assertSame($deliveryType->id, $orderItem->delivery_type_id);
        $this->assertSame('Livraison à domicile', $orderItem->delivery_type_label);
        $this->assertSame('15.00', $orderItem->price);
    }
}
