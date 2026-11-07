@extends('layouts::app')

@section('title', 'Mes commandes')

@section('body')
<main class="main">
    <div class="container py-5">
        <div class="mx-auto" style="max-width: 48rem;">
            <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                <div>
                    <p class="text-uppercase text-muted small font-weight-bold mb-1">Mon compte</p>
                    <h1 class="h3 mb-1">Mes commandes</h1>
                    @auth
                        <p class="text-muted small mb-0">Connecté en tant que <span class="font-weight-bold text-dark">{{ auth()->user()->email }}</span></p>
                    @endauth
                </div>

                @auth
                    <form action="{{ route('customer.logout') }}" method="POST" class="ml-3">
                        @csrf
                        <button type="submit" class="btn btn-outline-dark">Se déconnecter</button>
                    </form>
                @endauth
            </div>

            @if ($orders->isEmpty())
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5 text-center">
                        <h2 class="h5 mb-2">Aucune commande pour le moment</h2>
                        <p class="text-muted mb-4">Vos commandes passées avec ce compte apparaîtront ici.</p>
                        <a href="{{ route('shop.index') }}" class="btn btn-dark">Découvrir la boutique</a>
                    </div>
                </div>
            @else
                @foreach ($orders as $order)
                    @php
                        [$statusLabel, $statusClass] = match ($order->status) {
                            'paid' => ['Payée', 'badge-success'],
                            'cancelled' => ['Annulée', 'badge-secondary'],
                            'failed' => ['Échouée', 'badge-danger'],
                            default => ['En attente', 'badge-warning'],
                        };
                    @endphp
                    <section class="card border-0 shadow-sm mb-3">
                        <div class="card-body p-4">
                            <div class="d-flex flex-wrap align-items-start justify-content-between mb-2">
                                <div>
                                    <h2 class="h5 mb-1">Commande {{ $order->reference }}</h2>
                                    <p class="text-muted small mb-0">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                                <span class="badge {{ $statusClass }} badge-pill px-3 py-2">{{ $statusLabel }}</span>
                            </div>

                            <ul class="list-unstyled border-top mt-3 mb-3 pt-3">
                                @foreach ($order->items as $item)
                                    @php
                                        $details = collect([
                                            $item->variant_name,
                                            $item->product_option_name,
                                            $item->variant_option_name,
                                            $item->delivery_type_label,
                                        ])->filter();
                                    @endphp
                                    <li class="py-1">
                                        {{ $item->product?->title ?? 'Article' }}
                                        <span class="text-muted">× {{ $item->quantity }}</span>
                                        @if ($details->isNotEmpty())
                                            <br><small class="text-muted">{{ $details->implode(' / ') }}</small>
                                        @endif
                                        @if ($item->isDownloadable())
                                            <br><a href="{{ $item->downloadUrl(now()->addDays(7)) }}" class="btn btn-sm btn-outline-dark mt-2">Télécharger le PDF</a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>

                            <div class="d-flex justify-content-between align-items-center border-top pt-3">
                                <span class="text-muted">Total</span>
                                <span class="h5 mb-0">{{ number_format((float) $order->total, 2, ',', ' ') }} $</span>
                            </div>
                        </div>
                    </section>
                @endforeach

                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</main>
@endsection
