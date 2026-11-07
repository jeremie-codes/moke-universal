@php
    $productImages = $product->getMedia('images');
    $primaryImage = $productImages->first();
    $secondaryImage = $productImages->skip(1)->first();
    $imageUrl = $primaryImage?->getUrl()
        ?? ($product->cover_image
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($product->cover_image)
            : asset('assets/images/products/product-1.jpg'));
    $discount = $product->old_price && $product->old_price > $product->price
        ? round((($product->old_price - $product->price) / $product->old_price) * 100)
        : 0;
    $availableStock = $product->availableStock();
    $typeLabels = ['book' => 'Livre', 'comic' => 'Bande dessinée', 'clothing' => 'Vêtement'];
@endphp

<div class="product-default appear-animate" data-animation-name="fadeInRightShorter">
    <figure>
        <a href="{{ route('shop.show', $product->slug) }}">
            <img src="{{ $imageUrl }}" width="280" height="280" alt="{{ $product->title }}">
            @if ($secondaryImage)
            <img src="{{ $secondaryImage->getUrl() }}" width="280" height="280" alt="{{ $product->title }}">
            @else
            <img src="{{ $imageUrl }}" width="280" height="280" alt="{{ $product->title }}">
            @endif
        </a>
        <div class="label-group">
            @if ($discount > 0)
                <div class="product-label label-sale">-{{ $discount }}%</div>
            @endif
            @if ($availableStock < 1)
                <div class="product-label label-hot">Épuisé</div>
            @endif
        </div>
    </figure>
    <div class="product-details">
        <div class="category-list">
            <span class="product-category">{{ $product->category?->name ?? ($typeLabels[$product->type] ?? 'Produit') }}</span>
        </div>
        <h3 class="product-title"><a href="{{ route('shop.show', $product->slug) }}">{{ $product->title }}</a></h3>
        <div class="price-box">
            @if ($discount > 0)
                <del class="old-price">{{ number_format((float) $product->old_price, 2, ',', ' ') }} $</del>
            @endif
            <span class="product-price">{{ number_format((float) $product->price, 2, ',', ' ') }} $</span>
        </div>
        <!-- End .price-box -->
        <div class="product-action">
            @if ($product->options->isNotEmpty() || $product->variants->isNotEmpty() || $product->deliveryTypes->isNotEmpty())
                <a class="btn-icon btn-add-cart" href="{{ route('shop.show', $product->slug) }}"
                    @if ($availableStock < 1) aria-disabled="true" @endif>
                    <i class="fa fa-arrow-right"></i>
                    <span>{{ $availableStock > 0 ? 'CHOISIR LES OPTIONS' : 'ÉPUISÉ' }}</span>
                </a>
            @else
                <button type="button" class="btn-icon btn-add-cart js-add-to-cart"
                    data-url="{{ route('cart.items.store') }}" data-product-id="{{ $product->id }}"
                    data-product-name="{{ $product->title }}" data-product-image="{{ $imageUrl }}"
                    data-product-url="{{ route('shop.show', $product->slug) }}"
                    data-product-base-stock="{{ $availableStock }}" @disabled($availableStock < 1)>
                    <i class="fa fa-arrow-right"></i>
                    <span>AJOUTER AU PANIER</span>
                </button>
            @endif
        </div>
    </div>
</div>
