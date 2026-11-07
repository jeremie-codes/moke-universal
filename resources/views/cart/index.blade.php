@extends('layouts::app')

@section('title', 'Mon panier')

@section('body')
<main class="main">
    <div class="container py-4">
        <h1 class="h2 mb-4">Mon panier</h1>

        @if (session('error'))
            <div class="alert alert-warning" role="alert">{{ session('error') }}</div>
        @endif

        @if ($cartItems->isEmpty())
            <div class="text-center py-5 my-5">
                <i class="minicart-icon" style="width: 64px; height: 54px;"></i>
                <p class="text-center py-3
                " data-empty-cart>Votre panier est vide.</p>
            </div>
        @else
            <div class="row">
                <div class="col-lg-8">
                    <div class="cart-table-container">
                        <table class="table table-cart">
                            <thead>
                                <tr>
                                    <th class="thumbnail-col"></th>
                                    <th class="product-col">Produit</th>
                                    <th class="price-col">Prix unitaire</th>
                                    <th class="qty-col">Quantité</th>
                                    <th class="text-right">Sous-total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="cart-items">
                                @foreach ($cartItems as $item)
                                    @php
                                        $image = $item->product->getFirstMediaUrl('images')
                                            ?: ($item->product->cover_image
                                                ? \Illuminate\Support\Facades\Storage::disk('public')->url($item->product->cover_image)
                                                : asset('assets/images/products/product-1.jpg'));
                                        $lineSubtotal = (float) $item->price * $item->quantity;
                                        $allowedDeliveryTypes = $item->product->deliveryTypes;
                                    @endphp
                                    <tr class="product-row cart-line" data-unit-price="{{ $item->price }}">
                                        <td>
                                            <figure class="product-image-container">
                                                <a href="{{ route('shop.show', $item->product->slug) }}" class="product-image">
                                                    <img src="{{ $image }}" alt="{{ $item->product->title }}" width="80">
                                                </a>

                                                <a href="#" class="btn-remove js-remove-cart-item icon-cancel" title="Retirer {{ $item->product->title }} du panier"
                                                    data-url="{{ route('cart.items.destroy', $item->id) }}" aria-label="Retirer {{ $item->product->title }} du panier">
                                                </a>
                                            </figure>
                                        </td>
                                        <td class="product-col">
                                            <h5 class="product-title">{{ $item->product->title }}</h5>
                                            @php
                                                $selectedOptions = collect([
                                                    $item->variant?->name,
                                                    $item->productOption?->name,
                                                    $item->variantOption?->name,
                                                ])->filter();
                                            @endphp
                                            @if ($selectedOptions->isNotEmpty())
                                                <small class="text-muted">{{ $selectedOptions->implode(' / ') }}</small>
                                            @endif
                                            @if ($allowedDeliveryTypes->isNotEmpty())
                                                <div class="form-group mt-2 mb-0 p-0">
                                                    <label for="delivery-type-{{ $item->id }}">Mode de réception</label>
                                                    <select id="delivery-type-{{ $item->id }}"
                                                        class="form-control cart-delivery-type"
                                                        data-url="{{ route('cart.items.update', $item->id) }}"
                                                        data-saved-delivery-type="{{ $item->delivery_type_id ?? '' }}"
                                                        required>
                                                        <option value="">Choisir un mode de réception</option>
                                                        @foreach ($allowedDeliveryTypes as $deliveryType)
                                                            <option value="{{ $deliveryType->id }}"
                                                                @selected($item->delivery_type_id === $deliveryType->id)>
                                                                {{ $deliveryType->label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @elseif ($item->deliveryType)
                                                <small class="d-block text-warning">
                                                    Mode de réception non disponible : {{ $item->deliveryType->label }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>{{ number_format((float) $item->price, 2, ',', ' ') }} $</td>
                                        <td>
                                            <input class="form-control cart-quantity" type="number" min="1"
                                                max="{{ $item->availableStock() }}" value="{{ $item->quantity }}"
                                                data-stock="{{ $item->availableStock() }}"
                                                data-saved-quantity="{{ $item->quantity }}"
                                                data-url="{{ route('cart.items.update', $item->id) }}"
                                                aria-label="Quantité de {{ $item->product->title }}">
                                        </td>
                                        <td class="text-right">
                                            <span class="subtotal-price">{{ number_format($lineSubtotal, 2, ',', ' ') }} $</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="cart-summary">
                        <h2 class="h3">Récapitulatif</h2>
                        <table class="table table-totals">
                            <tbody>
                                <tr>
                                    <td>Sous-total</td>
                                    <td data-cart-subtotal>{{ number_format($subtotal, 2, ',', ' ') }} $</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>Total</td>
                                    <td data-cart-total>{{ number_format($subtotal, 2, ',', ' ') }} $</td>
                                </tr>
                            </tfoot>
                        </table>
                        <a href="{{ route('checkout.index') }}" class="btn btn-block btn-dark mt-3">Passer à la commande</a>
                        <a href="{{ route('home') }}" class="btn btn-block btn-danger">Continuer mes achats</a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</main>
@endsection
