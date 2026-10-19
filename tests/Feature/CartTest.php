<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

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
