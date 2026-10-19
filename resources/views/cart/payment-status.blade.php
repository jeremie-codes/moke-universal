@extends('layouts::app')

@section('title', 'Vérification du paiement')

@section('body')
<main class="main">
    <div class="container py-5">
        <div class="mx-auto text-center" style="max-width: 34rem;">
            <h1 class="h2">Vérification du paiement</h1>
            <p class="mb-4">Commande <strong>{{ $payment->order->reference }}</strong></p>

            <div data-payment-loader>
                <div class="spinner-border text-primary mb-3" role="status" aria-hidden="true"></div>
                <p class="mb-1">Confirmez le paiement sur votre téléphone.</p>
                <p class="text-muted">Vérification en cours pendant <strong data-payment-countdown>35</strong> secondes.</p>
            </div>

            <div class="alert d-none" data-payment-result role="status"></div>
            <a class="btn btn-dark d-none" data-payment-home href="{{ route('home') }}">Retour à la boutique</a>
        </div>
    </div>
</main>
@endsection

@section('script')
<script>
    (() => {
        const endpoint = @json(route('checkout.payment.check', $payment->reference));
        const loader = document.querySelector('[data-payment-loader]');
        const countdown = document.querySelector('[data-payment-countdown]');
        const result = document.querySelector('[data-payment-result]');
        const homeLink = document.querySelector('[data-payment-home]');
        const duration = 35;
        const startedAt = Date.now();
        let checking = false;
        let stopped = false;

        const finish = (status, message) => {
            if (stopped) return;

            stopped = true;
            window.clearInterval(countdownTimer);
            window.clearInterval(pollingTimer);
            loader.classList.add('d-none');
            result.textContent = message;
            result.className = `alert alert-${status === 'success' ? 'success' : 'danger'}`;
            homeLink.classList.remove('d-none');
        };

        const stopPending = () => {
            if (stopped) return;

            stopped = true;
            window.clearInterval(countdownTimer);
            window.clearInterval(pollingTimer);
            loader.classList.add('d-none');
            result.textContent = 'Le paiement est toujours en attente. Vous pouvez revenir plus tard pour vérifier son statut.';
            result.className = 'alert alert-warning';
            homeLink.classList.remove('d-none');
        };

        const checkStatus = async (isFinalAttempt = false) => {
            if (checking || stopped) return;

            checking = true;
            try {
                const response = await fetch(endpoint, { headers: { Accept: 'application/json' } });
                const data = await response.json();

                if (!response.ok) throw new Error(data.message || 'La vérification a échoué.');
                if (data.status !== 'pending') return finish(data.status, data.message);
                if (isFinalAttempt) stopPending();
            } catch (error) {
                if (isFinalAttempt) stopPending();
            } finally {
                checking = false;
            }
        };

        const countdownTimer = window.setInterval(() => {
            const elapsed = Math.floor((Date.now() - startedAt) / 1000);
            const remaining = Math.max(0, duration - elapsed);
            countdown.textContent = remaining;

            if (remaining === 0) {
                window.clearInterval(countdownTimer);
                checkStatus(true);
            }
        }, 250);

        const pollingTimer = window.setInterval(() => checkStatus(), 5000);
        checkStatus();
    })();
</script>
@endsection
