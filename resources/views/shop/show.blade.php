@extends('layouts::app')

@section('title', $product->title)

@section('style')
<style>
    .product-choice-list {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
    }

    .product-choice {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        min-height: 2.75rem;
        margin: 0;
        padding: .5rem .75rem;
        border: 1px solid #d7d7d7;
        border-radius: .25rem;
        cursor: pointer;
    }

    .product-choice.is-selected {
        border-color: #222;
        box-shadow: 0 0 0 1px #222;
    }

    .product-choice input:disabled + span {
        color: #888;
        text-decoration: line-through;
    }

    .product-choice.is-disabled {
        cursor: not-allowed;
        opacity: .6;
    }

    .product-choice-group legend {
        margin-bottom: .5rem;
        font-size: 1rem;
        font-weight: 600;
    }
</style>
@endsection

@section('body')
@php
    $productImages = $product->getMedia('images');
    $imageUrls = $productImages->map(fn ($image) => $image->getUrl())->all();
    if (empty($imageUrls) && $product->cover_image) {
        $imageUrls[] = \Illuminate\Support\Facades\Storage::disk('public')->url($product->cover_image);
    }
    if (empty($imageUrls)) {
        $imageUrls[] = asset('assets/images/products/product-1.jpg');
    }
    $discount = $product->old_price && $product->old_price > $product->price
        ? round((($product->old_price - $product->price) / $product->old_price) * 100)
        : 0;
    $availableStock = $product->availableStock();
    $hasVariantOptions = $product->variants->contains(fn ($variant) => $variant->options->isNotEmpty());
@endphp

<main class="main">
    <div class="container">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="icon-home"></i></a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Boutique</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->title }}</li>
            </ol>
        </nav>

        <div class="product-single-container product-single-default">
            <div class="row">
                <div class="col-lg-5 col-md-6 product-single-gallery">
                    <div class="product-slider-container">
                        <div class="product-single-carousel owl-carousel owl-theme show-nav-hover">
                            @foreach ($imageUrls as $imageUrl)
                                <div class="product-item">
                                    <img class="product-single-image" src="{{ $imageUrl }}"
                                        data-zoom-image="{{ $imageUrl }}" width="468" height="468"
                                        alt="{{ $product->title }}" />
                                </div>
                            @endforeach
                        </div>
                        <span class="prod-full-screen"><i class="icon-plus"></i></span>
                    </div>

                    <div class="prod-thumbnail owl-dots">
                        @foreach ($imageUrls as $imageUrl)
                            <div class="owl-dot">
                                <img src="{{ $imageUrl }}" width="110" height="110" alt="{{ $product->title }}" />
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-lg-7 col-md-6 product-single-details">
                    <h6 class="product-subtitle">{{ $product->category?->name ?? ucfirst($product->type) }}</h6>
                    <h1 class="product-title pt-0">{{ $product->title }}</h1>

                    <hr class="short-divider">

                    <div class="price-box">
                        @if ($discount > 0)
                            <del class="old-price">{{ number_format((float) $product->old_price, 2, ',', ' ') }} $</del>
                        @endif
                        <span class="new-price">{{ number_format((float) $product->price, 2, ',', ' ') }} $</span>
                    </div>

                    <div class="product-desc">
                        <p>{!! $product->description ?: 'Aucune description disponible.' !!}</p>
                    </div>

                    <div class="product-action">
                        @if ($availableStock > 0)
                            <form class="product-options-form mb-2" data-product-options>
                                @if ($product->options->isNotEmpty())
                                    <fieldset class="form-group product-choice-group">
                                        <legend>Option</legend>
                                        <div class="product-choice-list">
                                            @foreach ($product->options as $option)
                                                <label class="product-choice" for="product-option-{{ $option->id }}">
                                                    <input id="product-option-{{ $option->id }}" type="radio"
                                                        name="product_option_id" value="{{ $option->id }}"
                                                        data-stock="{{ $option->stock }}" required
                                                        @disabled($option->stock < 1)>
                                                    <span>{{ $option->name }}{{ $option->stock < 1 ? ' (Épuisé)' : '' }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </fieldset>
                                @endif

                                @if ($product->variants->isNotEmpty())
                                    <fieldset class="form-group product-choice-group">
                                        <legend>Variante</legend>
                                        <div class="product-choice-list">
                                            @foreach ($product->variants as $variant)
                                                <label class="product-choice" for="product-variant-{{ $variant->id }}">
                                                    <input id="product-variant-{{ $variant->id }}" type="radio"
                                                        name="variant_id" value="{{ $variant->id }}" required>
                                                    <span>{{ $variant->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </fieldset>
                                @endif

                                @if ($hasVariantOptions)
                                    <fieldset class="form-group product-choice-group" data-variant-option-group hidden>
                                        <legend>Option de la variante</legend>
                                        <div class="product-choice-list">
                                            @foreach ($product->variants as $variant)
                                                @foreach ($variant->options as $option)
                                                    <label class="product-choice" for="variant-option-{{ $option->id }}"
                                                        data-variant-id="{{ $variant->id }}" hidden>
                                                        <input id="variant-option-{{ $option->id }}" type="radio"
                                                            name="variant_option_id" value="{{ $option->id }}"
                                                            data-variant-id="{{ $variant->id }}"
                                                            data-stock="{{ $option->stock }}"
                                                            @disabled($option->stock < 1)>
                                                        <span>{{ $option->name }}{{ $option->stock < 1 ? ' (Épuisé)' : '' }}</span>
                                                    </label>
                                                @endforeach
                                            @endforeach
                                        </div>
                                    </fieldset>
                                @endif

                                @if ($product->deliveryTypes->isNotEmpty())
                                    <div class="form-group">
                                        <label for="delivery-type">Mode de réception</label>
                                        <select id="delivery-type" class="form-control" name="delivery_type_id" required>
                                            <option value="">Choisir un mode de réception</option>
                                            @foreach ($product->deliveryTypes as $deliveryType)
                                                <option value="{{ $deliveryType->id }}">{{ $deliveryType->label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </form>

                            <div class="product-single-qty">
                                <input class="horizontal-quantity form-control js-product-quantity" type="number"
                                    min="1" max="{{ $availableStock }}" value="1" aria-label="Quantité">
                            </div>
                            <button type="button" class="btn btn-dark add-cart mr-2 js-add-to-cart"
                                data-url="{{ route('cart.items.store') }}" data-product-id="{{ $product->id }}"
                                data-product-name="{{ $product->title }}" data-product-image="{{ $imageUrls[0] }}"
                                data-product-url="{{ route('shop.show', $product->slug) }}"
                                data-product-base-stock="{{ $availableStock }}">
                                <span>Ajouter au panier</span>
                            </button>
                        @else
                            <button type="button" class="btn btn-dark" disabled>Épuisé</button>
                        @endif
                        <a href="{{ route('cart.index') }}" class="btn btn-gray">Voir le panier</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="product-single-tabs">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="product-tab-desc" data-toggle="tab" href="#product-desc-content"
                        role="tab" aria-controls="product-desc-content" aria-selected="true">Description</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="product-desc-content" role="tabpanel"
                    aria-labelledby="product-tab-desc">
                    <div class="product-desc-content">
                        <p>{!! $product->description ?: 'Aucune description disponible.' !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
