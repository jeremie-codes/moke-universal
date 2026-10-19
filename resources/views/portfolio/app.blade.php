<!doctype html>
<html class="no-js" lang="zxx">


<!-- Mirrored from html.vecurosoft.com/ebukz/demo/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 23 Apr 2026 23:46:25 GMT -->

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title> @yield('title') | Moke</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">

    <!-- Web Fonts  -->
    <link id="googleFonts"
        href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800%7CShadows+Into+Light%7CPlayfair+Display:400&amp;display=swap"
        rel="stylesheet" type="text/css">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/animate/animate.compat.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/simple-line-icons/css/simple-line-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/owl.carousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/owl.carousel/assets/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/magnific-popup/magnific-popup.min.css') }}">

    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/theme-elements.css') }}">
    <link rel="stylesheet" href="{{ asset('css/theme-blog.css') }}">
    <link rel="stylesheet" href="{{ asset('css/theme-shop.css') }}">

    <!-- Skin CSS -->
    <link id="skinCSS" rel="stylesheet" href="{{ asset('css/skins/default.css') }}">

    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/svg+xml">
    <link rel="icon" href="{{ asset('assets/images/logo.png') }}" type="image/png" sizes="32x32">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/logo.png') }}">

</head>

<body data-plugin-page-transition>
    <div class="body">
        @include('portfolio.header')

        @yield('body')

        <footer id="footer" class="mt-0">
            <div class="container">
                <div class="row py-5">
                    <div class="col-md-4 d-flex justify-content-center justify-content-md-start mb-4 mb-lg-0">
                        <a href="index.html" class="logo pe-0 pe-lg-3 ps-3 ps-md-0">
                            <img alt="Moke Logo" width="82" height="35" src="{{ asset('assets/images/logo.png') }}">
                        </a>
                    </div>
                    <div class="col-md-8 d-flex justify-content-center justify-content-md-end mb-4 mb-lg-0">
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <div class="ms-3 text-center text-md-end">
                                    <h5 class="text-3 mb-0 text-color-light">NEW YORK</h5>
                                    <p class="text-3 text-color-hover-light mb-0">
                                        <i class="fab fa-whatsapp text-color-danger top-1 p-relative"></i>
                                        <a href="tel:+1234567890" class="ps-1">(123) 465-7890</a>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="ms-3 text-center text-md-end">
                                    <h5 class="text-3 mb-0 text-color-light">LOS ANGELES</h5>
                                    <p class="text-3 text-color-hover-light mb-0">
                                        <i class="fab fa-whatsapp text-color-danger top-1 p-relative"></i>
                                        <a href="tel:+1234567890" class="ps-1">(123) 465-7890</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-copyright footer-copyright-style-2">
                <div class="container py-2">
                    <div class="row py-4">
                        <div
                            class="col-md-4 d-flex align-items-center justify-content-center justify-content-md-start mb-2 mb-lg-0">
                            <p>© Copyright 2025. All Rights Reserved.</p>
                        </div>
                        <div
                            class="col-md-8 d-flex align-items-center justify-content-center justify-content-md-end mb-4 mb-lg-0">
                            <p><i class="far fa-envelope text-color-danger top-1 p-relative"></i><a
                                    href="https://www.okler.net/cdn-cgi/l/email-protection#9df0fcf4f1ddf8e5fcf0edf1f8b3fef2f0"
                                    class="text-color-hover-light ps-1"><span class="__cf_email__"
                                        data-cfemail="3954585055795c41585449555c175a5654">[email&#160;protected]</span></a>
                            </p>
                            <ul
                                class="footer-social-icons social-icons social-icons-clean social-icons-icon-light ms-3">
                                <li class="social-icons-facebook"><a href="http://www.facebook.com/" target="_blank"
                                        title="Facebook"><i class="fab fa-facebook-f text-2"></i></a></li>
                                <li class="social-icons-x"><a href="http://www.x.com/" target="_blank" title="X"><i
                                            class="fab fa-x-twitter text-2"></i></a></li>
                                <li class="social-icons-linkedin"><a href="http://www.linkedin.com/" target="_blank"
                                        title="Linkedin"><i class="fab fa-linkedin-in text-2"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>


    <!-- Vendor -->
    <script src="{{ asset('vendor/plugins/js/plugins.min.js') }}"></script>
    <!-- Theme Base, Components and Settings -->
    <script src="{{ asset('js/theme.js') }}"></script>
    <!-- Theme Initialization Files -->
    <script src="{{ asset('js/theme.init.js') }}"></script>
</body>

</html>
