@extends('layouts::app')

@section('title', 'Mon compte')

@section('body')
<main class="main">
    <div class="container py-5">
        <div class="mx-auto" style="max-width: 36rem;">
            <p class="text-uppercase text-muted small font-weight-bold mb-1">Mon compte</p>
            <h1 class="h3 mb-4">Mes informations</h1>

            @if (session('status'))
                <div class="alert alert-success" role="status">{{ session('status') }}</div>
            @endif

            <form action="{{ route('customer.account.update') }}" method="POST" class="card border-0 shadow-sm">
                @csrf
                @method('PUT')

                <div class="card-body p-4">
                    <div class="form-group">
                        <label for="account-name">Nom</label>
                        <input id="account-name" name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}" autocomplete="name" required maxlength="255">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="account-email">Adresse e-mail</label>
                        <input id="account-email" name="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}" autocomplete="email" required maxlength="255">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="account-phone">Téléphone</label>
                        <input id="account-phone" name="phone" type="tel" class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone', $user->phone) }}" autocomplete="tel" maxlength="40">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="account-address">Adresse de livraison</label>
                        <textarea id="account-address" name="address" class="form-control @error('address') is-invalid @enderror"
                            rows="3" autocomplete="street-address" maxlength="2000">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>

                    <h2 class="h5 mb-1">Changer le mot de passe</h2>
                    <p class="text-muted small mb-3">Laissez ces champs vides si vous ne souhaitez pas modifier votre mot de passe.</p>

                    <div class="form-group">
                        <label for="account-current-password">Mot de passe actuel</label>
                        <input id="account-current-password" name="current_password" type="password"
                            class="form-control @error('current_password') is-invalid @enderror" autocomplete="current-password">
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="account-password">Nouveau mot de passe</label>
                        <input id="account-password" name="password" type="password"
                            class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" minlength="8">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="account-password-confirmation">Confirmer le nouveau mot de passe</label>
                        <input id="account-password-confirmation" name="password_confirmation" type="password"
                            class="form-control" autocomplete="new-password" minlength="8">
                    </div>

                    <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-3">
                        <a href="{{ route('customer.orders') }}">Mes commandes</a>
                        <button type="submit" class="btn btn-dark">Enregistrer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
