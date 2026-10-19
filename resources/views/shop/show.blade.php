@extends('layouts::app')

@section('title', $product->title)

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
                    <h1 class="product-title">{{ $product->title }}</h1>

                    <hr class="short-divider">

                    <div class="price-box">
                        @if ($discount > 0)
                            <del class="old-price">{{ number_format((float) $product->old_price, 2, ',', ' ') }} $</del>
                        @endif
                        <span class="new-price">{{ number_format((float) $product->price, 2, ',', ' ') }} $</span>
                    </div>

                    <div class="product-desc">
                        <p>{{ $product->description ?: 'Aucune description disponible.' }}</p>
                    </div>

                    <ul class="single-info-list">
                        <li>Référence : <strong>{{ $product->sku }}</strong></li>
                        <li>Catégorie : <strong>{{ $product->category?->name ?? ucfirst($product->type) }}</strong></li>
                        <li>Disponibilité : <strong>{{ $product->stock > 0 ? $product->stock . ' en stock' : 'Épuisé' }}</strong></li>
                    </ul>

                    <div class="product-action">
                        @if ($product->stock > 0)
                            <div class="product-single-qty">
                                <input class="horizontal-quantity form-control js-product-quantity" type="number"
                                    min="1" max="{{ $product->stock }}" value="1" aria-label="Quantité">
                            </div>
                            <button type="button" class="btn btn-dark add-cart mr-2 js-add-to-cart"
                                data-url="{{ route('cart.items.store') }}" data-product-id="{{ $product->id }}"
                                data-product-name="{{ $product->title }}" data-product-image="{{ $imageUrls[0] }}"
                                data-product-url="{{ route('shop.show', $product->slug) }}">
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
                        <p>{{ $product->description ?: 'Aucune description disponible.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
