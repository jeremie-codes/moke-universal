@extends('layouts::app')

@section('title', 'Finaliser ma commande')

@section('body')
<main class="main">
    <div class="container checkout-container py-4">
        <ul class="checkout-progress-bar d-flex justify-content-center flex-wrap">
            <li><a href="{{ route('cart.index') }}">Panier</a></li>
            <li class="active"><span>Coordonnées et livraison</span></li>
            <li class="disabled"><span>Paiement</span></li>
        </ul>

        <h1 class="h2 mb-4">Finaliser ma commande</h1>
        <h2 class="step-title">Coordonnées de livraison</h2>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-7">
                <section class="checkout-steps">
                    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
                        @csrf
                        <div class="form-group">
                            <label for="fullname">Nom complet <abbr class="required" title="obligatoire">*</abbr></label>
                            <input id="fullname" name="fullname" type="text" class="form-control"
                                value="{{ old('fullname') }}" autocomplete="name" required maxlength="255">
                        </div>

                        <div class="form-group">
                            <label for="email">Adresse e-mail <abbr class="required" title="obligatoire">*</abbr></label>
                            <input id="email" name="email" type="email" class="form-control"
                                value="{{ old('email') }}" autocomplete="email" required maxlength="255">
                        </div>

                        <div class="form-group">
                            <label for="phone">Téléphone <abbr class="required" title="obligatoire">*</abbr></label>
                            <input id="phone" name="phone" type="tel" class="form-control"
                                value="{{ old('phone') }}" autocomplete="tel" required maxlength="40">
                        </div>

                        <div class="form-group">
                            <label for="address">Adresse complète <abbr class="required" title="obligatoire">*</abbr></label>
                            <textarea id="address" name="address" class="form-control" rows="4"
                                autocomplete="street-address" required maxlength="2000">{{ old('address') }}</textarea>
                        </div>

                        <fieldset class="form-group">
                            <legend class="h5">Mode de paiement</legend>
                            <div class="custom-control custom-radio mb-2">
                                <input class="custom-control-input" id="payment-mobile" name="payment_method" type="radio" value="mobile"
                                    @checked(old('payment_method', 'mobile') === 'mobile')>
                                <label class="custom-control-label" for="payment-mobile">Mobile Money</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" id="payment-card" name="payment_method" type="radio" value="card"
                                    @checked(old('payment_method') === 'card')>
                                <label class="custom-control-label" for="payment-card">Carte bancaire</label>
                            </div>
                        </fieldset>

                        <div class="form-group" data-payment-phone-group>
                            <label for="payment-phone">Numéro Mobile Money <abbr class="required" title="obligatoire">*</abbr></label>
                            <input id="payment-phone" name="payment_phone" type="tel"
                                class="form-control @error('payment_phone') is-invalid @enderror"
                                value="{{ old('payment_phone') }}" autocomplete="tel" maxlength="40"
                                data-payment-phone>
                            <small class="form-text text-muted">Ce numéro sera utilisé uniquement pour le paiement Mobile Money.</small>
                            @error('payment_phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <p class="text-muted">Les paiements sont traités de manière sécurisée par FlexPay.</p>
                        <button type="submit" class="btn btn-dark btn-place-order">Procéder au paiement</button>
                    </form>
                </section>
            </div>

            <div class="col-lg-5">
                <section class="order-summary bg-light">
                    <h2 class="h3">Récapitulatif</h2>
                    <table class="table table-mini-cart">
                        <thead>
                            <tr><th>Produit</th><th class="text-right">Total</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($cartItems as $item)
                                <tr class="cart-item">
                                    <td class="product-col">
                                        <h3 class="product-title">
                                            {{ $item->product->title }} × {{ $item->quantity }}
                                        </h3>
                                    </td>
                                    <td class="price-col text-right">
                                        {{ number_format((float) $item->price * $item->quantity, 2, ',', ' ') }} $
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Sous-total</th>
                                <td class="text-right">{{ number_format($subtotal, 2, ',', ' ') }} $</td>
                            </tr>
                            <tr class="order-total">
                                <th>Total</th>
                                <td class="text-right"><strong>{{ number_format($subtotal, 2, ',', ' ') }} $</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                    <a href="{{ route('cart.index') }}" class="btn btn-light mt-2">Modifier le panier</a>
                </section>
            </div>
        </div>
    </div>
</main>
@endsection

@section('script')
<script>
    (() => {
        const paymentPhoneGroup = document.querySelector('[data-payment-phone-group]');
        const paymentPhone = document.querySelector('[data-payment-phone]');
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');

        const updatePaymentPhone = () => {
            const isMobilePayment = document.querySelector('input[name="payment_method"]:checked')?.value === 'mobile';
            paymentPhoneGroup.classList.toggle('d-none', !isMobilePayment);
            paymentPhone.required = isMobilePayment;
        };

        paymentMethods.forEach((method) => method.addEventListener('change', updatePaymentPhone));
        updatePaymentPhone();
    })();
</script>
@endsection
