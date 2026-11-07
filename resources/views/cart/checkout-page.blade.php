@extends('layouts::app')

@section('title', 'Finaliser ma commande')

@section('body')
@php
    $customerMode = auth()->check() ? 'account' : old('customer_mode', 'guest');
@endphp
<main class="main">
    <div class="container checkout-container py-4">
        <ul class="checkout-progress-bar d-flex justify-content-center flex-wrap">
            <li><a href="{{ route('cart.index') }}">Panier</a></li>
            <li class="active"><span>Coordonnées et livraison</span></li>
            <li class="disabled"><span>Paiement</span></li>
        </ul>

        <h1 class="h2 mb-4">Finaliser ma commande</h1>
        <h2 class="step-title">Votre compte client</h2>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success" role="status">{{ session('status') }}</div>

            <script>
                setTimeout(() => {
                    document.querySelector('.alert.alert-success').remove();
                }, 5000); // Remove the success alert after 5 seconds
            </script>
        @endif

        <div class="row">
            <div class="col-lg-7">
                <section class="checkout-steps">
                    @auth
                        <div class="alert alert-info">
                           <div>
                                Vous êtes connecté en tant que <strong>{{ auth()->user()->email }}</strong>. <br>
                                Votre compte vous permet de suivre vos commandes et les articles achetés.
                                <a href="{{ route('customer.orders') }}" class="d-block mt-2">Suivre mes commandes</a>
                            </div>
                        </div>
                        <form action="{{ route('customer.logout') }}" method="POST" class="mb-4">
                            @csrf
                            <button type="submit" class="btn btn-link px-0">Se déconnecter</button>
                        </form>
                    @else
                        <fieldset class="form-group mb-4">
                            <legend class="h5">Comment souhaitez-vous poursuivre ?</legend>
                            <div class="custom-control custom-radio mb-2">
                                <input class="custom-control-input" id="customer-guest" name="customer_mode" type="radio"
                                    value="guest" @checked($customerMode === 'guest')>
                                <label class="custom-control-label" for="customer-guest">
                                    Commander sans compte
                                </label>
                            </div>
                            <div class="custom-control custom-radio mb-2">
                                <input class="custom-control-input" id="customer-login" name="customer_mode" type="radio"
                                    value="login" @checked($customerMode === 'login')>
                                <label class="custom-control-label" for="customer-login">
                                    Me connecter pour suivre mes commandes et mes articles achetés
                                </label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input class="custom-control-input" id="customer-register" name="customer_mode" type="radio"
                                    value="register" @checked($customerMode === 'register')>
                                <label class="custom-control-label" for="customer-register">
                                    Créer un compte client
                                </label>
                            </div>
                        </fieldset>

                        <form action="{{ route('customer.login') }}" method="POST"
                            class="mb-4 {{ $customerMode === 'login' ? '' : 'd-none' }}" data-customer-panel="login">
                            @csrf
                            <input type="hidden" name="customer_mode" value="login">
                            <div class="form-group">
                                <label for="login-email">Adresse e-mail</label>
                                <input id="login-email" name="email" type="email" class="form-control"
                                    value="{{ old('email') }}" autocomplete="email" required>
                            </div>
                            <div class="form-group">
                                <label for="login-password">Mot de passe</label>
                                <input id="login-password" name="password" type="password" class="form-control"
                                    autocomplete="current-password" required>
                            </div>
                            <button type="submit" class="btn btn-dark">Se connecter</button>
                        </form>

                        <form action="{{ route('customer.register') }}" method="POST"
                            class="mb-4 {{ $customerMode === 'register' ? '' : 'd-none' }}" data-customer-panel="register">
                            @csrf
                            <input type="hidden" name="customer_mode" value="register">
                            <div class="form-group">
                                <label for="register-name">Nom complet</label>
                                <input id="register-name" name="name" type="text" class="form-control"
                                    value="{{ old('name') }}" autocomplete="name" maxlength="255" required>
                            </div>
                            <div class="form-group">
                                <label for="register-email">Adresse e-mail</label>
                                <input id="register-email" name="email" type="email" class="form-control"
                                    value="{{ old('email') }}" autocomplete="email" required>
                            </div>
                            <div class="form-group">
                                <label for="register-password">Mot de passe (8 caractères minimum)</label>
                                <input id="register-password" name="password" type="password" class="form-control"
                                    autocomplete="new-password" minlength="8" required>
                            </div>
                            <div class="form-group">
                                <label for="register-password-confirmation">Confirmer le mot de passe</label>
                                <input id="register-password-confirmation" name="password_confirmation" type="password"
                                    class="form-control" autocomplete="new-password" minlength="8" required>
                            </div>
                            <button type="submit" class="btn btn-dark">Créer mon compte</button>
                        </form>
                    @endauth

                    <h2 class="step-title">Coordonnées de livraison</h2>
                    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form"
                        class="{{ !auth()->check() && $customerMode !== 'guest' ? 'd-none' : '' }}"
                        data-checkout-details>
                        @csrf
                        <input type="hidden" name="customer_mode" value="{{ $customerMode }}">
                        @auth
                            <div class="checkout-delivery-summary card card-body bg-light mb-4 d-flex flex-row justify-content-between align-items-start">
                                <div>
                                    <strong>{{ auth()->user()->name }}</strong><br>
                                    <span>{{ auth()->user()->email }}</span><br>
                                    <span>{{ auth()->user()->phone ?: 'Téléphone non renseigné' }}</span><br>
                                    <span style="white-space: pre-line">{{ auth()->user()->address ?: 'Adresse non renseignée' }}</span>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-dark ml-3 flex-shrink-0"
                                    data-delivery-toggle aria-expanded="false" aria-controls="delivery-fields">Modifier</button>
                            </div>
                        @endauth
                        <div id="delivery-fields" class="{{ auth()->check() ? 'd-none' : '' }}" data-delivery-fields>
                        <div class="form-group">
                            <label for="fullname">Nom complet <abbr class="required" title="obligatoire">*</abbr></label>
                            <input id="fullname" name="fullname" type="text" class="form-control"
                                value="{{ old('fullname', auth()->user()?->name) }}" autocomplete="name" required maxlength="255">
                        </div>

                        <div class="form-group">
                            <label for="email">Adresse e-mail <abbr class="required" title="obligatoire">*</abbr></label>
                            <input id="email" name="email" type="email" class="form-control"
                                value="{{ old('email', auth()->user()?->email) }}" autocomplete="email" required maxlength="255">
                        </div>

                        <div class="form-group">
                            <label for="phone">Téléphone <abbr class="required" title="obligatoire">*</abbr></label>
                            <input id="phone" name="phone" type="tel" class="form-control"
                                value="{{ old('phone', auth()->user()?->phone) }}" autocomplete="tel" required maxlength="40">
                        </div>

                        <div class="form-group">
                            <label for="address">Adresse complète <abbr class="required" title="obligatoire">*</abbr></label>
                            <textarea id="address" name="address" class="form-control" rows="4"
                                autocomplete="street-address" required maxlength="2000">{{ old('address', auth()->user()?->address) }}</textarea>
                        </div>
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

                        <p class="alert alert-info">Les paiements sont traités de manière sécurisée par FlexPaie.</p>
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
                                        @if ($item->deliveryType)
                                            <small class="d-block text-muted">
                                                Réception : {{ $item->deliveryType->label }}
                                            </small>
                                        @endif
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
        const customerModes = document.querySelectorAll('input[name="customer_mode"]');
        const customerPanels = document.querySelectorAll('[data-customer-panel]');
        const checkoutDetails = document.querySelector('[data-checkout-details]');
        const checkoutCustomerMode = checkoutDetails?.querySelector('[name="customer_mode"]');

        const updatePaymentPhone = () => {
            const isMobilePayment = document.querySelector('input[name="payment_method"]:checked')?.value === 'mobile';
            paymentPhoneGroup.classList.toggle('d-none', !isMobilePayment);
            paymentPhone.required = isMobilePayment;
        };

        paymentMethods.forEach((method) => method.addEventListener('change', updatePaymentPhone));
        updatePaymentPhone();

        const updateCustomerMode = () => {
            const mode = document.querySelector('input[name="customer_mode"]:checked')?.value || 'guest';
            customerPanels.forEach((panel) => {
                panel.classList.toggle('d-none', panel.dataset.customerPanel !== mode);
            });
            if (checkoutDetails) checkoutDetails.classList.toggle('d-none', mode !== 'guest');
            if (checkoutCustomerMode) checkoutCustomerMode.value = mode;
        };

        customerModes.forEach((mode) => mode.addEventListener('change', updateCustomerMode));
        updateCustomerMode();

        const deliveryFields = document.querySelector('[data-delivery-fields]');
        const deliveryToggle = document.querySelector('[data-delivery-toggle]');
        const placeOrderButton = document.querySelector('.btn-place-order');

        const setDeliveryOpen = (open) => {
            if (!deliveryFields || !deliveryToggle) return;
            deliveryFields.classList.toggle('d-none', !open);
            deliveryToggle.setAttribute('aria-expanded', String(open));
            deliveryToggle.textContent = open ? 'Masquer' : 'Modifier';
        };

        deliveryToggle?.addEventListener('click', () => {
            setDeliveryOpen(deliveryFields.classList.contains('d-none'));
        });

        // Les champs cachés bloqueraient la validation du formulaire : on les déplie si un champ requis est vide.
        placeOrderButton?.addEventListener('click', () => {
            if (!deliveryFields || !deliveryFields.classList.contains('d-none')) return;
            const hasInvalidField = [...deliveryFields.querySelectorAll('input, textarea')].some((field) => !field.checkValidity());
            if (hasInvalidField) setDeliveryOpen(true);
        });
    })();
</script>
@endsection
