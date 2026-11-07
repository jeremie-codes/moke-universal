<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\DeliveryType;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeliveryTypeTest extends TestCase
{
    use RefreshDatabase;

    public function test_default_delivery_types_can_be_assigned_to_products(): void
    {
        $category = Category::create(['name' => 'Test']);
        $product = Product::create([
            'category_id' => $category->id,
            'title' => 'Produit avec réception',
            'type' => 'book',
            'price' => '10.00',
            'stock' => 1,
        ]);
        $deliveryTypes = DeliveryType::query()->get()->keyBy('type');

        $this->assertSame(
            ['digital', 'home_delivery', 'pickup'],
            $deliveryTypes->keys()->sort()->values()->all(),
        );

        $product->deliveryTypes()->sync([
            $deliveryTypes['digital']->id,
            $deliveryTypes['pickup']->id,
        ]);

        $this->assertSame(
            ['digital', 'pickup'],
            $product->deliveryTypes()->orderBy('type')->pluck('type')->all(),
        );
        $this->assertDatabaseHas('product_delivery_types', [
            'product_id' => $product->id,
            'delivery_type_id' => $deliveryTypes['digital']->id,
        ]);

        $this->get(route('shop.show', $product->slug))
            ->assertOk()
            ->assertSee('name="delivery_type_id"', false)
            ->assertSeeText('Électronique')
            ->assertSeeText('Retrait en présentiel');
    }
}
