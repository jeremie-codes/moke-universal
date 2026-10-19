@extends('layouts::app')

@section('title', 'Commande enregistrée')

@section('body')
<main class="main">
    <div class="container py-5 text-center">
        <h1 class="h2">Votre commande est enregistrée</h1>
        <p>Référence : <strong>{{ $order->reference }}</strong></p>
        <p>Statut : <strong>En attente de paiement</strong></p>
        <p>Le paiement n’a pas été effectué. Les options de paiement seront ajoutées ultérieurement.</p>
        <a href="{{ route('home') }}" class="btn btn-dark mt-3">Retour à la boutique</a>
    </div>
</main>
@endsection
