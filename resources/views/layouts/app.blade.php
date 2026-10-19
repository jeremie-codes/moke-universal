<!doctype html>
<html class="no-js" lang="zxx">


<!-- Mirrored from html.vecurosoft.com/ebukz/demo/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 23 Apr 2026 23:46:25 GMT -->

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title> @yield('title') | Moke</title>

    @include('layouts::head')

    @yield('style')

</head>

<body>
    <div class="page-wrapper">
        @include('layouts::header')

        @yield('body')

        @include('layouts::footer')
    </div>

    @include("layouts::mobilemenu")

    {{-- Cart Mini Popup when an item is added to the cart --}}
    <div class="cart-mini-popup" data-cart-mini-popup role="status" aria-live="polite" aria-atomic="true" hidden>
        <a data-cart-mini-popup-link href="#" tabindex="-1">
            <img class="cart-mini-popup__image" data-cart-mini-popup-image src="" alt="">
        </a>
        <div class="cart-mini-popup__content">
            <a class="cart-mini-popup__title" data-cart-mini-popup-title href="#"></a>
            <p class="cart-mini-popup__message">a été ajouté à votre panier.</p>
        </div>
        <button class="cart-mini-popup__close" type="button" data-cart-mini-popup-close aria-label="Fermer">&times;</button>
    </div>

    @include('layouts::script')

    @yield('script')
</body>

</html>
