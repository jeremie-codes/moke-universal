@extends('layouts::app')

@section('title', 'Accueil')

@section('body')

<main class="main">
    <div class="home-slider slide-animate owl-carousel owl-theme show-nav-hover nav-big mb-2 text-uppercase"
        data-owl-options="{'loop': false }">
        <div class="home-slide home-slide1 banner">
            <img class="slide-bg" src="{{ asset('assets/images/demoes/demo4/slider/slide-1.jpg') }}" width="1903"
                height="499" alt="slider image">
            <div class="container d-flex align-items-center justify-content-between">
                <div class="banner-layer appear-animate" data-animation-name="fadeInUpShorter">
                    <h4 class="text-transform-none m-b-3">Nouveaux Livres, bientôt disponible!</h4>
                    <h2 class="text-transform-none mb-0">Comming soon...</h2>
                    {{-- <h3 class="m-b-3">70% Off</h3> --}}
                    <h3 class="m-b-3">FALTA</h3>
                    <h5 class="d-inline-block mb-0">
                        <span>à Seulement</span>
                        <b class="coupon-sale-text text-white bg-secondary align-middle">
                            <sup>$</sup>
                            <em class="align-text-top">39</em>
                            <sup>,99</sup>
                        </b>
                    </h5>
                    <a href="{{ route('shop.index') }}" class="btn btn-dark btn-lg">Réserver!</a>
                </div>

                {{-- <div class="col-4" style="border-radius: 15px; width: 40%; align-self: flex-end !important;">
                    <img src="{{ asset('assets/images/auteur.jpeg') }}" alt="banner image">
                </div> --}}
                <!-- End .banner-layer -->
            </div>
        </div>
        <!-- End .home-slide -->

        <div class="home-slide home-slide2 banner banner-md-vw">
            <img class="slide-bg" style="background-color: #ccc;" width="1903" height="499"
                src="{{ asset('assets/images/demoes/demo4/slider/slide-2.jpg') }}" alt="slider image">
            <div class="container d-flex align-items-center">
                <div class="banner-layer d-flex justify-content-center appear-animate"
                    data-animation-name="fadeInUpShorter">
                    <div class="mx-auto">
                        <h4 class="m-b-1">Vente des</h4>
                        <h3 class="m-b-2">Vêtements &</h3>
                        <h3 class="mb-2 heading-border">Accessories</h3>
                        <h2 class="text-transform-none m-b-4">Livres</h2>
                        <a href="{{ route('shop.index') }}" class="btn btn-block btn-dark">Voir la boutique</a>
                    </div>
                </div>
                <!-- End .banner-layer -->
            </div>
        </div>
        <!-- End .home-slide -->
    </div>
    <!-- End .home-slider -->

    <div class="container">
        <div class="info-boxes-slider owl-carousel owl-theme mb-2" data-owl-options="{'dots': false,'loop': false,'responsive': {'576': {'items': 2},'992': {'items': 3}}}">
            <div class="info-box info-box-icon-left">
                <i class="icon-shipping"></i>

                <div class="info-box-content">
                    <h4>LIVRAISON ET RETOUR GRATUITS</h4>
                    <p class="text-body">Livraison disponible partout à partir de deux articles vêtements.</p>
                </div>
                <!-- End .info-box-content -->
            </div>
            <!-- End .info-box -->

            <div class="info-box info-box-icon-left">
                <i class="icon-money"></i>

                <div class="info-box-content">
                    <h4>GARANTIE DE REMBOURSEMENT</h4>
                    <p class="text-body">Garantie de remboursement à 100%</p>
                </div>
                <!-- End .info-box-content -->
            </div>
            <!-- End .info-box -->

            <div class="info-box info-box-icon-left">
                <i class="icon-support"></i>

                <div class="info-box-content">
                    <h4>ASSISTANCE EN LIGNE 24H/24 et 7J/7</h4>
                    <p class="text-body">Pour une meilleure expérience utilisateur.</p>
                </div>
                <!-- End .info-box-content -->
            </div>
            <!-- End .info-box -->
        </div>
        <!-- End .info-boxes-slider -->

        <div class="banners-container mb-2">
            <div class="banners-slider owl-carousel owl-theme" data-owl-options="{'dots': false }">
                <div class="banner banner1 banner-sm-vw d-flex align-items-center appear-animate"
                    style="background-color: #ccc;" data-animation-name="fadeInLeftShorter" data-animation-delay="500">
                    <figure class="w-100">
                        <img src="{{ asset('assets/images/demoes/demo4/banners/banner-1.jpg') }}" alt="banner"
                            width="380" height="175" />
                    </figure>
                    <div class="banner-layer">
                        <h3 class="m-b-2">Bande dessinnée</h3>
                        <h4 class="m-b-3 text-primary"><sup class="text-dark"><del>10%</del></sup>15%<sup>de
                                réduction</sup></h4>
                        <a href="#section-livres" class="btn btn-sm btn-dark">Voir Plus</a>
                    </div>
                </div>
                <!-- End .banner -->

                <div class="banner banner2 banner-sm-vw text-uppercase d-flex align-items-center appear-animate"
                    data-animation-name="fadeInUpShorter" data-animation-delay="200">
                    <figure class="w-100">
                        <img src="{{ asset('assets/images/demoes/demo4/banners/banner-2.jpg') }}"
                            style="background-color: #ccc;" alt="banner" width="380" height="175" />
                    </figure>
                    <div class="banner-layer text-center">
                        <div class="row align-items-lg-center">
                            <div class="col-lg-7 text-lg-right">
                                <h3>Offre promos</h3>
                                <h4 class="pb-4 pb-lg-0 mb-0 text-body">A partir de deux articles</h4>
                            </div>
                            <br>
                            <div class="col-lg-5 text-lg-left px-0 px-xl-3">
                                <a href="#section-vetements" class="btn btn-sm btn-dark">Voir plus</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End .banner -->

                <div class="banner banner3 banner-sm-vw d-flex align-items-center appear-animate"
                    style="background-color: #ccc;" data-animation-name="fadeInRightShorter" data-animation-delay="500">
                    <figure class="w-100">
                        <img src="{{ asset('assets/images/demoes/demo4/banners/banner-3.jpg') }}" alt="banner"
                            width="380" height="175" />
                    </figure>
                    <div class="banner-layer text-right">
                        <h3 class="m-b-2">Vêtements</h3>
                        <h4 class="m-b-2 text-secondary text-uppercase">Nouveau look</h4>
                        <a href="#section-vetements" class="btn btn-sm btn-dark">Acheter maintenant</a>
                    </div>
                </div>
                <!-- End .banner -->
            </div>
        </div>
    </div>
    <!-- End .container -->

    <section class="featured-products-section" id="section-livres">
        <div class="container">
            <h2 class="section-title heading-border ls-20 border-0">Produits vedettes</h2>

            <div class="products-slider custom-products owl-carousel owl-theme nav-outer show-nav-hover nav-image-center" data-owl-options="{'dots': false,'nav': true}">
                @forelse ($featuredProducts as $product)
                    @include('components.product-card', ['product' => $product])
                @empty
                    <p class="text-center">Aucun produit disponible pour le moment.</p>
                @endforelse
            </div>
            <!-- End .featured-proucts -->
        </div>
    </section>

    <section class="promo-section bg-dark" data-parallax="{'speed': 2, 'enableOnMobile': true}"
        data-image-src="{{ asset('assets/images/demoes/demo4/banners/banner-5.jpg') }}">
        <div class="promo-banner banner container text-uppercase">
            <div class="banner-content row align-items-center text-center">
                <div class="col-md-4 ml-xl-auto text-md-right appear-animate" data-animation-name="fadeInRightShorter"
                    data-animation-delay="600">
                    <h2 class="mb-md-0 text-white">Top Fashion<br>Deals</h2>
                </div>
                <div class="col-md-4 col-xl-3 pb-4 pb-md-0 appear-animate" data-animation-name="fadeIn"
                    data-animation-delay="300">
                    <a href="{{ route('shop.index') }}" class="btn btn-dark btn-black ls-10">Voir Plus</a>
                </div>
                <div class="col-md-4 mr-xl-auto text-md-left appear-animate" data-animation-name="fadeInLeftShorter"
                    data-animation-delay="600">
                    <h4 class="mb-1 mt-1 font1 coupon-sale-text p-0 d-block ls-n-10 text-transform-none">
                        <b>OFFRE PROMOS</b>
                    </h4>
                    <h5 class="mb-1 coupon-sale-text text-white ls-10 p-0">
                        <i class="ls-0 mr-3">A PARTIR</i>
                        <b class="text-white bg-secondary ls-n-10">$100</b>
                    </h5>
                </div>
            </div>
        </div>
    </section>

    <section class="new-products-section pb-0" id="section-vetements">
        <div class="container">
            <h2 class="section-title heading-border ls-20 border-0">Nouveautés - vêtements</h2>

            <div class="products-slider custom-products owl-carousel owl-theme nav-outer show-nav-hover nav-image-center mb-2"
                data-owl-options="{'dots': false,'nav': true,'responsive': {'992': {'items': 3},'1200': {'items': 4}}}">

                @forelse ($clothingProducts as $product)
                    @include('components.product-card', ['product' => $product])
                @empty
                    <p class="text-center">Aucun vêtement disponible pour le moment.</p>
                @endforelse
            </div>
            <!-- End .featured-proucts -->

            <div class="banner banner-big-sale appear-animate" data-animation-delay="200" data-animation-name="fadeInUpShorter"
                style="background: #2A95CB center/cover url('{{ asset('assets/images/demoes/demo4/banners/banner-4.jpg') }}');">
                <div class="banner-content row align-items-center mx-0">
                    <div class="col-md-9 col-sm-8">
                        <h2 class="text-white text-uppercase text-center text-sm-left ls-n-20 mb-md-0 px-4">
                            <b class="d-inline-block mr-3 mb-1 mb-md-0">Dave Moke</b> Auteur de livre,
                            <span class="text-transform-none text-white align-middle"
                                style="font-size: 14px; font-weight: 500;">Laisser nous votre avis pour aider les autres à nous faire aussi confiance!</span>
                        </h2>
                    </div>
                    <div class="col-md-3 col-sm-4 text-center text-sm-right">
                        <a class="btn btn-light btn-white btn-lg" href="category.html">Donnez un avis</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mt-0 testimonial-section">
        <div class="container">
            <div class="row">
                <div class="col-xl-5">
                    <div class="banner-img">
                        <img src="{{ asset('assets/images/auteur.jpeg') }}" alt="banner image" style="border-radius: 15px;">
                        <div class="banner-content">
                            <span class="sub-title">Auteur</span>
                            <h2 class="banner-title">Laissez votre avis</h2>
                            <a class="btn btn-light btn-white btn-lg" href="#!">Donner un avis</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="text-left title-area wow animate__fadeInUp title-anime animation-style5"
                        data-wow-delay="0.25s">
                        <h4 class="sec-subtitle left-shape justify-content-center title-anime__title">Témoignages</h4>
                        <h2 class="sec-title title-anime__title">Ce que disent les gens sur nous et nos produits
                        </h2>
                        <p class="sec-text">
                            Vos avis et commentaires sont important pour permettre aux autres de nous faire
                            confiance, n'hésitez pas à nous laisser un commentaire.
                        </p>
                    </div>

                    <div class="owl-carousel owl-theme owl-dots-simple mb-4 mb-lg-0 appear-animate"
                        data-owl-options="{'loop': false,'dots': true,'margin': 20,'responsive': null}"
                        data-animation-name="fadeInRightShorter" data-animation-delay="200">

                        @forelse ($temoignages as $temoignage)
                        <div class="testimonial bg-blue testimonial-color testimonial-type3 mb-0">
                            <blockquote class="text-white">
                                <p class="text-white">{{ $temoignage->content }}</p>
                            </blockquote>
                            <div class="testimonial-arrow-down"></div>
                            <div class="testimonial-owner">
                                <figure>
                                    <img src="{{ asset('assets/images/info.png') }}" width="100" height="100"
                                        alt="client">
                                </figure>
                                <div>
                                    <strong class="testimonial-title">{{ $temoignage->name }}</strong>
                                    <span>{{ $temoignage->role ?? 'Client' }}</span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="testimonial bg-blue testimonial-color testimonial-type3 mb-0">
                            <blockquote class="text-white">
                                <p class="text-white">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                    Donec hendrerit vehicula est, in consequat. Lorem ipsum dolor sit amet,
                                    consectetur adipiscing elit. Donec hendrerit vehicula est, in consequat.</p>
                            </blockquote>
                            <div class="testimonial-arrow-down"></div>
                            <div class="testimonial-owner">
                                <figure>
                                    <img src="{{ asset('assets/images/info.png') }}" width="100" height="100"
                                        alt="client">
                                </figure>
                                <div>
                                    <strong class="testimonial-title">John Smith</strong>
                                    <span>CEO & Founder - Okler</span>
                                </div>
                            </div>
                        </div>

                        <div class="testimonial bg-blue testimonial-color testimonial-type3 mb-0">
                            <blockquote class="text-white">
                                <p class="text-white">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                    Donec hendrerit vehicula est, in consequat. Lorem ipsum dolor sit amet,
                                    consectetur adipiscing elit. Donec hendrerit vehicula est, in consequat.
                                    Donec hendrerit vehicula est, in consequat. Donec hendrerit vehicula est, in
                                    consequat.</p>
                            </blockquote>
                            <div class="testimonial-arrow-down"></div>
                            <div class="testimonial-owner">
                                <figure>
                                    <img src="{{ asset('assets/images/info.png') }}" width="100" height="100"
                                        alt="client">
                                </figure>
                                <div>
                                    <strong class="testimonial-title">John Smith</strong>
                                    <span>CEO & Founder - Okler</span>
                                </div>
                            </div>
                        </div>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="blog-section pb-0">
        <div class="container">
            <h2 class="section-title heading-border border-0 appear-animate" data-animation-name="fadeInUp">Partenaires
                & Sponsors</h2>
            <div class="brands-slider owl-carousel owl-theme images-center appear-animate" data-animation-name="fadeIn"
                data-animation-duration="500" data-owl-options="{'margin': 0}">
                @forelse ($sponsors as $sponsor)
                <img src="{{ asset('storage/' . $sponsor->logo) }}" width="130" height="56" alt="brand">
                @empty
                <img src="{{ asset('assets/images/logo.png') }}" width="130" height="56" alt="brand">
                <img src="{{ asset('assets/images/brands/brand1.webp') }}" width="130" height="56" alt="brand">
                <img src="{{ asset('assets/images/brands/brand.png') }}" width="130" height="56" alt="brand">
                <img src="{{ asset('assets/images/brands/brand7.png') }}" width="130" height="56" alt="brand">
                <img src="{{ asset('assets/images/brands/brand2.png') }}" width="130" height="56" alt="brand">
                <img src="{{ asset('assets/images/brands/brand3.png') }}" width="130" height="56" alt="brand">
                <img src="{{ asset('assets/images/brands/brand4.png') }}" width="130" height="56" alt="brand">
                <img src="{{ asset('assets/images/brands/brand5.png') }}" width="130" height="56" alt="brand">
                <img src="{{ asset('assets/images/brands/brand6.png') }}" width="130" height="56" alt="brand">
                @endforelse
            </div>

            <hr class="mt-4 m-b-5">

            <!-- End .brands-slider -->
        </div>
    </section>
</main>
<!-- End .main -->
@if (!session('newsletter_success'))
<div class="newsletter-popup mfp-hide bg-img" id="newsletter-popup-form" style="background: #f1f1f1 no-repeat center/cover url({{ asset('assets/images/newsletter.png') }})">
    <div class="newsletter-popup-content">
        <img src="{{ asset('assets/images/logo.png') }}" width="111" height="44" alt="Logo" class="logo-newsletter">
        <h2>Inscrivez-vous à la newsletter</h2>

        <p>
            Inscrivez-vous à la liste de diffusion de Porto pour recevoir des mises à jour sur les nouvelles arrivées, les offres spéciales et nos promotions.
        </p>

        <form action="{{ route('newsletter.store') }}" method="POST">
            @csrf
            <div class="input-group">
                <input type="email" class="form-control border @error('email') is-invalid @enderror" id="newsletter-email" name="email"
                    value="{{ old('email') }}" placeholder="Votre adresse e-mail" required />
                <input type="submit" class="btn btn-dark" value="S'inscrire" />
            </div>
            @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </form>
    </div>
    <!-- End .newsletter-popup-content -->

    <button title="Close (Esc)" type="button" class="mfp-close">
        ×
    </button>
</div>
<!-- End .newsletter-popup -->
@endif

@if (session('newsletter_success'))
    <div class="alert alert-success" id="alert-newsletter" style="position: fixed; top: 80px; right: 10px; z-index: 999" role="status">
        {{ session('newsletter_success') }}
    </div>

    <script>
        // force hide alert after 7 sec
        setTimeout(() => {
            document.querySelector('#alert-newsletter').classList.add('d-none')
        }, 7000);
    </script>
@endif
@endsection
