<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Category;
use App\Models\DeliveryType;
use App\Models\Option;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withCredentials()
            ->withCookie(config('session.cookie'), str_repeat('a', 40));
    }

    public function test_adding_a_product_creates_a_cart_line_and_updates_the_count(): void
    {
        $product = $this->createProduct(stock: 5, price: '12.50');

        $this->postJson(route('cart.items.store'), ['product_id' => $product->id])
            ->assertOk()
            ->assertJson([
                'cart_count' => 1,
                'subtotal' => 12.5,
            ]);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => '12.50',
        ]);
    }

    public function test_changing_quantity_updates_line_and_cart_totals(): void
    {
        $product = $this->createProduct(stock: 5, price: '12.50');
        $this->postJson(route('cart.items.store'), ['product_id' => $product->id])->assertOk();
        $item = CartItem::query()->firstOrFail();

        $this->patchJson(route('cart.items.update', $item), ['quantity' => 3])
            ->assertOk()
            ->assertJson([
                'cart_count' => 3,
                'subtotal' => 37.5,
                'line_subtotal' => 37.5,
                'quantity' => 3,
            ]);
    }

    public function test_quantity_cannot_exceed_product_stock(): void
    {
        $product = $this->createProduct(stock: 2, price: '12.50');
        $this->postJson(route('cart.items.store'), ['product_id' => $product->id])->assertOk();
        $item = CartItem::query()->firstOrFail();

        $this->patchJson(route('cart.items.update', $item), ['quantity' => 3])
            ->assertUnprocessable();

        $this->assertDatabaseHas('cart_items', [
            'id' => $item->id,
            'quantity' => 1,
        ]);
    }

    public function test_variant_option_controls_cart_price_and_stock(): void
    {
        $product = $this->createProduct(stock: 0, price: '12.50');
        $variant = Variant::create([
            'product_id' => $product->id,
            'name' => 'Rouge',
        ]);
        $option = Option::create([
            'variant_id' => $variant->id,
            'name' => 'M',
            'stock' => 2,
        ]);

        $this->postJson(route('cart.items.store'), [
            'product_id' => $product->id,
            'variant_id' => $variant->id,
            'variant_option_id' => $option->id,
        ])
            ->assertOk()
            ->assertJson(['cart_count' => 1, 'subtotal' => 12.5]);

        $item = CartItem::query()->firstOrFail();
        $this->assertSame($variant->id, $item->variant_id);
        $this->assertSame($option->id, $item->variant_option_id);
        $this->assertSame('12.50', $item->price);

        $this->patchJson(route('cart.items.update', $item), ['quantity' => 3])
            ->assertUnprocessable();
    }

    public function test_product_option_can_set_stock_without_a_variant(): void
    {
        $product = $this->createProduct(stock: 10, price: '12.50');
        $option = Option::create([
            'product_id' => $product->id,
            'name' => 'L',
            'stock' => 1,
        ]);

        $this->postJson(route('cart.items.store'), [
            'product_id' => $product->id,
            'product_option_id' => $option->id,
        ])->assertOk()->assertJson(['subtotal' => 12.5]);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'product_option_id' => $option->id,
            'variant_id' => null,
        ]);
    }

    public function test_variant_must_belong_to_the_selected_product(): void
    {
        $product = $this->createProduct(stock: 5, price: '12.50');
        $otherCategory = Category::create(['name' => 'Other test category']);
        $otherProduct = Product::create([
            'category_id' => $otherCategory->id,
            'title' => 'Autre produit',
            'type' => 'book',
            'price' => '12.50',
            'stock' => 5,
            'is_active' => true,
        ]);
        $variant = Variant::create([
            'product_id' => $otherProduct->id,
            'name' => 'Bleu',
        ]);

        $this->postJson(route('cart.items.store'), [
            'product_id' => $product->id,
            'variant_id' => $variant->id,
        ])->assertUnprocessable();

        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_cart_delivery_type_must_be_enabled_for_the_product(): void
    {
        $product = $this->createProduct(stock: 5, price: '12.50');
        $pickup = DeliveryType::query()->where('type', 'pickup')->firstOrFail();
        $digital = DeliveryType::query()->where('type', 'digital')->firstOrFail();
        $product->deliveryTypes()->attach($pickup);

        $this->postJson(route('cart.items.store'), [
            'product_id' => $product->id,
            'delivery_type_id' => $digital->id,
        ])->assertUnprocessable();

        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_cart_can_change_and_display_an_enabled_delivery_type(): void
    {
        $product = $this->createProduct(stock: 5, price: '12.50');
        $pickup = DeliveryType::query()->where('type', 'pickup')->firstOrFail();
        $homeDelivery = DeliveryType::query()->where('type', 'home_delivery')->firstOrFail();
        $product->deliveryTypes()->attach([$pickup->id, $homeDelivery->id]);

        $this->postJson(route('cart.items.store'), [
            'product_id' => $product->id,
            'delivery_type_id' => $pickup->id,
        ])->assertOk();
        $item = CartItem::query()->firstOrFail();

        $this->patchJson(route('cart.items.update', $item), [
            'delivery_type_id' => $homeDelivery->id,
        ])->assertOk()->assertJson(['delivery_type_id' => $homeDelivery->id]);

        $this->get(route('cart.index'))
            ->assertOk()
            ->assertSeeText('Mode de réception')
            ->assertSeeText('Livraison à domicile');

        $this->assertDatabaseHas('cart_items', [
            'id' => $item->id,
            'delivery_type_id' => $homeDelivery->id,
        ]);
    }

    private function createProduct(int $stock, string $price): Product
    {
        $category = Category::create(['name' => 'Test']);

        return Product::create([
            'category_id' => $category->id,
            'title' => 'Produit de test',
            'type' => 'book',
            'price' => $price,
            'stock' => $stock,
            'is_active' => true,
        ]);
    }
}
