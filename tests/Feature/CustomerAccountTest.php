<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerAccountTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withCredentials()
            ->withCookie(config('session.cookie'), str_repeat('a', 40));
    }

    public function test_customer_can_register_during_checkout_and_claim_their_cart(): void
    {
        $product = $this->createProduct();
        $this->postJson(route('cart.items.store'), ['product_id' => $product->id])->assertOk();

        $this->post(route('customer.register'), [
            'name' => 'Client Test',
            'email' => 'client@example.com',
            'password' => 'password-123',
            'password_confirmation' => 'password-123',
            'customer_mode' => 'register',
        ])->assertRedirect(route('checkout.index'));

        $user = User::query()->where('email', 'client@example.com')->firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->assertTrue(Hash::check('password-123', $user->password));
        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'session_id' => $this->app['session.store']->getId(),
        ]);

        $this->get(route('checkout.index'))
            ->assertOk()
            ->assertSeeText('Vous êtes connecté')
            ->assertSee('value="Client Test"', false)
            ->assertSee('value="client@example.com"', false)
            ->assertSee(route('customer.orders'), false);
    }

    public function test_customer_can_login_and_account_order_history_is_private(): void
    {
        $user = User::create([
            'name' => 'Compte Client',
            'email' => 'existing@example.com',
            'password' => 'password-123',
        ]);
        $product = $this->createProduct();
        $this->postJson(route('cart.items.store'), ['product_id' => $product->id])->assertOk();
        $cart = Cart::query()->firstOrFail();

        $this->post(route('customer.login'), [
            'email' => 'existing@example.com',
            'password' => 'password-123',
            'customer_mode' => 'login',
        ])->assertRedirect(route('checkout.index'));

        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseHas('carts', ['id' => $cart->id, 'user_id' => $user->id]);

        $order = Order::create([
            'user_id' => $user->id,
            'fullname' => $user->name,
            'email' => $user->email,
            'subtotal' => '12.50',
            'total' => '12.50',
        ]);
        $order->items()->create([
            'product_id' => $product->id,
            'price' => '12.50',
            'quantity' => 1,
        ]);

        $this->get(route('customer.orders'))
            ->assertOk()
            ->assertSeeText($order->reference)
            ->assertSeeText($product->title);
    }

    public function test_guest_can_choose_anonymous_checkout_and_account_creation(): void
    {
        $product = $this->createProduct();
        $this->postJson(route('cart.items.store'), ['product_id' => $product->id])->assertOk();

        $this->get(route('checkout.index'))
            ->assertOk()
            ->assertSeeText('Commander sans compte')
            ->assertSeeText('Me connecter pour suivre mes commandes')
            ->assertSeeText('Créer un compte client');

        $this->get(route('customer.orders'))->assertRedirect(route('checkout.index'));
    }

    private function createProduct(): Product
    {
        $category = Category::create(['name' => 'Account test category']);

        return Product::create([
            'category_id' => $category->id,
            'title' => 'Produit suivi',
            'type' => 'book',
            'price' => '12.50',
            'stock' => 5,
            'is_active' => true,
        ]);
    }
}
