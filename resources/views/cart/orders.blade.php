@extends('layouts::app')

@section('title', 'Mes commandes')

@section('body')
<main class="main">
    <div class="container py-4">
        <h1 class="h2 mb-4">Mes commandes</h1>
        <p>Retrouvez ici les commandes passées avec votre compte.</p>

        @if ($orders->isEmpty())
            <div class="alert alert-info">Vous n’avez pas encore passé de commande avec ce compte.</div>
        @else
            @foreach ($orders as $order)
                @php
                    $statusLabel = match ($order->status) {
                        'paid' => 'Payée',
                        'cancelled' => 'Annulée',
                        'failed' => 'Échouée',
                        default => 'En attente',
                    };
                @endphp
                <section class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between">
                            <h2 class="h5">Commande {{ $order->reference }}</h2>
                            <span>{{ $statusLabel }}</span>
                        </div>
                        <p class="text-muted mb-2">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                            · Total : {{ number_format((float) $order->total, 2, ',', ' ') }} $
                        </p>
                        <ul class="mb-0">
                            @foreach ($order->items as $item)
                                <li>
                                    {{ $item->product?->title ?? 'Article' }} × {{ $item->quantity }}
                                    @php
                                        $details = collect([
                                            $item->variant_name,
                                            $item->product_option_name,
                                            $item->variant_option_name,
                                            $item->delivery_type_label,
                                        ])->filter();
                                    @endphp
                                    @if ($details->isNotEmpty())
                                        <span class="text-muted">({{ $details->implode(' / ') }})</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </section>
            @endforeach

            {{ $orders->links() }}
        @endif
    </div>
</main>
@endsection
