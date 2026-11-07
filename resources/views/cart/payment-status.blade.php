@extends('layouts::app')

@section('title', 'Vérification du paiement')

@section('body')
<style>
    .payment-spinner { display: inline-block; width: 3rem; height: 3rem; border: .3em solid currentColor; border-right-color: transparent; border-radius: 50%; animation: payment-spin .75s linear infinite; }
    @keyframes payment-spin { to { transform: rotate(360deg); } }
</style>

<main class="main">
    <div class="container py-5">
        <div class="mx-auto" style="max-width: 36rem;">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-md-5 text-center">
                    <p class="text-uppercase text-muted small font-weight-bold mb-2">Paiement</p>
                    <h1 class="h3 mb-1">Vérification du paiement</h1>
                    <p class="text-muted mb-4">
                        Commande <span class="font-weight-bold text-dark">{{ $payment->order->reference }}</span>
                    </p>

                    <div data-payment-loader>
                        <div class="payment-spinner text-primary mb-3" role="status" aria-hidden="true"></div>
                        <p class="font-weight-bold mb-1">Confirmez le paiement sur votre téléphone</p>
                        <p class="text-muted small mb-4">
                            Vérification en cours pendant <strong data-payment-countdown>35</strong> secondes.
                        </p>
                        <div class="progress mx-auto" style="height: 6px; max-width: 20rem;" aria-hidden="true">
                            <div class="progress-bar" data-payment-progress style="width: 100%; transition: width .25s linear;"></div>
                        </div>
                    </div>

                    <div class="alert d-none text-left mt-4 mb-0" data-payment-result role="status"></div>
                    <div class="text-left mt-3 d-none" data-payment-downloads>
                        <p class="font-weight-bold mb-2">Vos fichiers à télécharger</p>
                        <ul class="list-unstyled mb-0" data-payment-download-list></ul>
                    </div>
                    <a class="btn btn-dark btn-lg mt-4 d-none" data-payment-home href="{{ route('home') }}">Retour à la boutique</a>
                </div>
            </div>
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
        const progress = document.querySelector('[data-payment-progress]');
        const result = document.querySelector('[data-payment-result]');
        const homeLink = document.querySelector('[data-payment-home]');
        const downloadsBox = document.querySelector('[data-payment-downloads]');
        const downloadList = document.querySelector('[data-payment-download-list]');
        const duration = 35;
        const startedAt = Date.now();
        let checking = false;
        let stopped = false;

        const startDownloads = (downloads) => {
            if (!downloads.length) return;

            downloads.forEach((file) => {
                const item = document.createElement('li');
                const link = document.createElement('a');
                link.href = file.url;
                link.textContent = file.label;
                link.className = 'btn btn-outline-dark btn-sm mb-2';
                item.appendChild(link);
                downloadList.appendChild(item);
            });
            downloadsBox.classList.remove('d-none');

            downloads.forEach((file, index) => window.setTimeout(() => {
                const link = document.createElement('a');
                link.href = file.url;
                link.download = '';
                link.hidden = true;
                document.body.appendChild(link);
                link.click();
                link.remove();
            }, index * 1500));
        };

        const finish = (status, message, downloads = []) => {
            if (stopped) return;

            stopped = true;
            window.clearInterval(countdownTimer);
            window.clearInterval(pollingTimer);
            loader.classList.add('d-none');
            result.textContent = message;
            result.className = `alert alert-${status === 'success' ? 'success' : 'danger'}`;
            homeLink.classList.remove('d-none');

            if (status === 'success') startDownloads(downloads);
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
                if (data.status !== 'pending') return finish(data.status, data.message, data.downloads ?? []);
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
            progress.style.width = `${(remaining / duration) * 100}%`;

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
