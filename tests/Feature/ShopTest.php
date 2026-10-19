<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopTest extends TestCase
{
    use RefreshDatabase;

    public function test_shop_lists_active_products_and_links_to_product_details(): void
    {
        $product = $this->createProduct('Livre disponible');
        $this->createProduct('Livre non publié', isActive: false);

        $this->get(route('shop.index'))
            ->assertOk()
            ->assertSeeText('Livre disponible')
            ->assertDontSeeText('Livre non publié')
            ->assertSee(route('shop.show', $product->slug), false);
    }

    public function test_product_detail_shows_product_information_and_add_to_cart_control(): void
    {
        $product = $this->createProduct('Un produit précis');

        $this->get(route('shop.show', $product->slug))
            ->assertOk()
            ->assertSeeText('Un produit précis')
            ->assertSeeText('Description du produit')
            ->assertSee('data-product-id="' . $product->id . '"', false)
            ->assertSee(route('cart.items.store'), false);
    }

    private function createProduct(string $title, bool $isActive = true): Product
    {
        $category = Category::create(['name' => 'Test']);

        return Product::create([
            'category_id' => $category->id,
            'title' => $title,
            'type' => 'book',
            'price' => '12.50',
            'description' => 'Description du produit',
            'stock' => 5,
            'is_active' => $isActive,
        ]);
    }
}
