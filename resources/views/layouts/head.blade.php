<meta name="author" content="Dave Moke">
<meta name="description" content="Plateforme de vente d'article en ligne et Portfolio">
<meta name="keywords" content="Plateforme de vente d'article en ligne et Portfolio">
<meta name="robots" content="INDEX,FOLLOW">

<!-- Mobile Specific Metas -->
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Favicons -->
<link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/svg+xml">
<link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/png" sizes="32x32">
<link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon">
<link rel="apple-touch-icon" href="{{ asset('assets/images/logo.png') }}">

<script>
    WebFontConfig = {
        google: {
            families: ['Open+Sans:300,400,600,700,800', 'Poppins:300,400,500,600,700,800',
                'Oswald:300,400,500,600,700,800'
            ]
        }
    };
    (function(d) {
        var wf = d.createElement('script'),
            s = d.scripts[0];
        wf.src = 'assets/js/webfont.js';
        wf.async = true;
        s.parentNode.insertBefore(wf, s);
    })(document);
</script>


<!-- Plugins CSS File -->
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

<!-- Main CSS File -->
<link rel="stylesheet" href="{{ asset('assets/css/demo4.min.css') }}">

<link rel="stylesheet" href="{{ asset('assets/css/style.min.css') }}">

{{--
<link rel="stylesheet" href="{{ asset('assets/css/style.min.css') }}"> --}}
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}">

<style>
    .cart-mini-popup {
        position: fixed;
        top: 90%;
        left: 50%;
        z-index: 1100;
        width: min(23rem, calc(100vw - 2rem));
        padding: 1rem;
        display: grid;
        grid-template-columns: 4rem 1fr auto;
        gap: .875rem;
        align-items: center;
        background: #fff;
        border: 1px solid #e1e1e1;
        box-shadow: 0 .75rem 1.75rem rgba(0, 0, 0, .16);
        opacity: 0;
        transform: translate(-50%, calc(-50% + 1rem));
        pointer-events: none;
        transition: opacity .2s ease, transform .2s ease;
    }

    .cart-mini-popup.is-visible {
        opacity: 1;
        transform: translate(-50%, -50%);
        pointer-events: auto;
    }

    .cart-mini-popup__image {
        width: 4rem;
        height: 4rem;
        object-fit: cover;
    }

    .cart-mini-popup__content {
        min-width: 0;
    }

    .cart-mini-popup__title {
        display: block;
        overflow: hidden;
        color: inherit;
        font-weight: 600;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .cart-mini-popup__message {
        margin: .25rem 0 0;
        font-size: .875rem;
    }

    .cart-mini-popup__close {
        border: 0;
        background: transparent;
        color: inherit;
        font-size: 1.5rem;
        line-height: 1;
    }
</style>
