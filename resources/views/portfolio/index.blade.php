@extends('portfolio.app')

@section('title', 'Portfolio')

@section('body')
<div role="main" class="main">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 text-center text-lg-start">
                <h2 class="font-weight-bold text-8 line-height-2 appear-animation"
                    data-appear-animation="fadeInRightShorter" data-appear-animation-delay="500">
                    <span class="text-5">Salut, Moi c'est <span class="text-color-danger">Dave Moke</span>
                        mon profil
                    </span>
                </h2>

                <h2 data-appear-animation="fadeInRightShorter" data-appear-animation-delay="500"
                    class="word-rotator slide font-weight-semi-bold text-8 line-height-2 appear-animation">

                    <span>Je suis un </span>

                    <span class="word-rotator-words bg-quaternary">
                        @forelse ($skills as $skill)
                        <b class="{{ $loop->first ? 'is-visible' : '' }}">{{ $skill->name }}</b>
                        @empty
                        <b class="is-visible">Artiste</b>
                        <b>Graphiste</b>
                        <b>Photographe</b>
                        @endforelse
                    </span>
                    <span> talentueux.</span>

                </h2>
            </div>

            <div class="col-lg-3 text-center text-lg-end">
                <img class="img-fluid appear-animation" data-appear-animation="fadeInRightShorter"
                    data-appear-animation-delay="600" src="{{ asset('assets/images/auteur.png') }}" alt="Auteur Image">
            </div>

            <div class="col-lg-2 text-center text-lg-end">
                <a class="btn btn-outline btn-rounded btn-primary btn-with-arrow text-hover-primary mb-5 mb-lg-2 appear-animation"
                    data-appear-animation="fadeInRightShorter" data-appear-animation-delay="1100" href="#">
                    Contactez moi<span><i class="fas fa-chevron-right"></i></span>
                </a>

                {{-- <ul class="social-icons social-icons-clean-with-border social-icons-medium"> --}}
                    <ul class="list list-icons list-icons-style-3 d-flex align-items-center justify-content-center appear-animation"
                        data-appear-animation="fadeInRightShorter" data-appear-animation-delay="1100">
                        <li>
                            <a href="{{ $settings->instagram ?? '#!' }}" class="text-white" target="_blank"
                                title="Instagram"><i class="fab fa-instagram"></i></a>
                        </li>
                        <li>
                            <a href="{{ $settings->twitter ?? '#!' }}" class="text-white" target="_blank"
                                title="Twitter"><i class="fab fa-x-twitter"></i></a>
                        </li>
                        <li>
                            <a href="{{ $settings->facebook ?? '#!' }}" class="text-white" target="_blank"
                                title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        </li>
                        <li>
                            <a href="{{ $settings->youtube ?? '#!' }}" class="text-white" target="_blank"
                                title="YouTube"><i class="fab fa-youtube"></i></a>
                        </li>
                    </ul>
            </div>
        </div>

        <div class="appear-animation align-items-center" data-appear-animation="fadeIn"
            data-appear-animation-delay="500">
            @if($settings)
            <p class="alternative-font-4 p-0 mb-0" data-plugin-animated-letters
                data-plugin-options="{'startDelay': 700, 'minWindowWidth': 0, 'animationName': 'typeWriter', 'animationSpeed': 50}">
                {{ $settings->bio }}
            </p>
            @else
            <p class="alternative-font-4 mb-0" data-plugin-animated-letters
                data-plugin-options="{'startDelay': 700, 'minWindowWidth': 0, 'animationName': 'typeWriter', 'animationSpeed': 50}">
                Bienvenue sur mon portfolio. Découvrez mes compétences et mes réalisations dans divers domaines
                créatifs.
            </p>
            @endif
        </div>
    
        <div class="appear-animation" data-appear-animation="fadeIn" data-appear-animation-delay="500">
            <hr class="my-5">
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col">
                <div class="appear-animation" data-appear-animation="fadeIn" data-appear-animation-delay="500">
                    <ul class="nav nav-pills sort-source sort-source-style-3" data-sort-id="portfolio"
                        data-option-key="filter" data-plugin-options="{'layoutMode': 'masonry', 'filter': '*'}">
                        <li class="nav-item active" data-option-value="*"><a
                                class="nav-link text-2-5 text-uppercase active" href="#">Tous</a></li>
                        <li class="nav-item" data-option-value=".painture"><a class="nav-link text-2-5 text-uppercase"
                                href="#">Painture</a></li>
                        <li class="nav-item" data-option-value=".tableau"><a class="nav-link text-2-5 text-uppercase"
                                href="#">Tableau</a></li>
                        <li class="nav-item" data-option-value=".graphic"><a class="nav-link text-2-5 text-uppercase"
                                href="#">Graphic</a></li>
                        <li class="nav-item" data-option-value=".autres"><a class="nav-link text-2-5 text-uppercase"
                                href="#">Autres</a></li>
                    </ul>

                    <div class="sort-destination-loader sort-destination-loader-showing mt-4 pt-2">
                        <div class="row portfolio-list sort-destination" data-sort-id="portfolio">

                            @foreach($projects as $project)
                            <div class="col-sm-6 col-lg-3 isotope-item {{ $project->categorie }}">
                                <div class="portfolio-item">
                                    <a href="{{ route('portfolio.show', $project) }}">
                                        <span
                                            class="thumb-info thumb-info-centered-info thumb-info-no-borders border-radius-0">
                                            <span class="thumb-info-wrapper border-radius-0">
                                                <img src="{{ $project->getFirstMediaUrl('images') }}"
                                                    class="img-fluid border-radius-0" alt="{{ $project->title }}">
                                                <span class="thumb-info-title">
                                                    <span class="thumb-info-inner">{{ $project->title }}</span>
                                                    <span class="thumb-info-type">{{ ucfirst($project->categorie)
                                                        }}</span>
                                                </span>
                                            </span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                            @endforeach

                            {{ $projects->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="appear-animation" data-appear-animation="fadeIn" data-appear-animation-delay="500">
            <hr class="my-5">
        </div>

    </div>

    <section class="section section-no-border py-0 mt-5 mb-0 container-fluid">
        <div class="row">
            <div class="col-lg-6 p-0">
                <section class="parallax section section-parallax h-100 m-0" data-plugin-parallax
                    data-plugin-options="{'speed': 1.5, 'horizontalPosition': '100%'}"
                    data-image-src="{{ asset('assets/images/parallax-2.jpg') }}" style="min-height: 315px;">
                </section>
            </div>
            <div class="col-lg-6 p-0">
                <div class="h-100 m-0">
                    <div class="row m-0">
                        <div class="col-half-section col-half-section-left py-5">
                            <div class="p-3">
                                <h4 class="font-weight-bold mb-2 mt-4 mt-md-0 text-6 mb-3">Compétences</h4>
                                <ul class="list list-icons list-icons-style-3 list-primary">
                                    @forelse ($skills as $skill)
                                    <li><i class="fas fa-check"></i> {{ $skill->name }}</li>
                                    @empty
                                    <li><i class="fas fa-check"></i> Aucune compétence disponible</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 order-2 order-lg-1 p-0">
                <div class="h-100 m-0">
                    <div class="row m-0">
                        <div class="col-half-section col-half-section-right py-5 ms-auto">
                            <div class="p-3">
                                <h4 class="font-weight-bold mb-2 mt-4 mt-md-0 text-6 mb-3">Expériences</h4>
                                <ul class="list list-icons list-icons-style-3 list-quaternary">
                                    @forelse ($experiences as $experience)
                                    <li><i class="fas fa-check"></i> {{ $experience->title }}</li>
                                    @empty
                                    <li><i class="fas fa-check"></i> Aucune expérience disponible</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 order-1 order-lg-2 p-0">
                <section class="parallax section section-parallax custom-parallax-bg-pos-left custom-sec-left h-100 m-0"
                    data-plugin-parallax data-plugin-options="{'speed': 1.5, 'horizontalPosition': '100%'}"
                    data-image-src="{{ asset('assets/images/parallax-3.jpg') }}" style="min-height: 315px;">
                </section>
            </div>
        </div>
    </section>

    <div class="container py-4">

        <ul class="nav nav-pills sort-source sort-source-style-3 justify-content-center" data-sort-id="team"
            data-option-key="filter">
            <li class="nav-item active" data-option-value="*">
                <a class="nav-link text-2-5 text-uppercase active" href="#">
                    Collaborateurs
                </a>
            </li>
        </ul>

        <div class="sort-destination-loader sort-destination-loader-showing mt-4 pt-2">
            <div class="row team-list sort-destination" data-sort-id="team">

                @forelse ($teams as $team)
                <div class="col-12 col-sm-6 col-lg-3 isotope-item development">
                    <span class="thumb-info thumb-info-hide-wrapper-bg mb-4">
                        <span class="thumb-info-wrapper">
                            <a href="#!">
                                <img src="{{ asset('storage/' . $team->avatar) }}" class="img-fluid" alt="">
                                <span class="thumb-info-title">
                                    <span class="thumb-info-inner">{{ $team->name }}</span>
                                    <span class="thumb-info-type">{{ $team->expertise }}</span>
                                </span>
                            </a>
                        </span>
                        <span class="thumb-info-caption">
                            <span class="thumb-info-caption-text">
                                {{ $team->description }}
                            </span>
                            <span class="thumb-info-social-icons mb-4">
                                @if ($team->facebook)
                                <a target="_blank" href="{{ $team->facebook }}">
                                    <i class="fab fa-facebook-f"></i>
                                    <span>Facebook</span>
                                </a>
                                @endif
                                @if ($team->twitter)
                                <a target="_blank" href="{{ $team->twitter }}">
                                    <i class="fab fa-x-twitter"></i>
                                    <span>Twitter</span>
                                </a>
                                @endif
                                @if ($team->linkedin)
                                <a target="_blank" href="{{ $team->linkedin }}">
                                    <i class="fab fa-linkedin-in"></i>
                                    <span>Linkedin</span>
                                </a>
                                @endif
                                @if ($team->instagram)
                                <a target="_blank" href="{{ $team->instagram }}">
                                    <i class="fab fa-instagram"></i>
                                    <span>Instagram</span>
                                </a>
                                @endif
                            </span>
                        </span>
                    </span>
                </div>
                @empty

                @endforelse

            </div>
        </div>

    </div>
</div>
@endsection