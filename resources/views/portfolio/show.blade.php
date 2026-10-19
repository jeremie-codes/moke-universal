@extends('portfolio.app')

@section('title', 'Portfolio')

@section('body')

<div role="main" class="main">

    <section class="page-header page-header-modern bg-color-grey page-header-md ">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col">
                    <div class="row">
                        <div class="col-md-12 align-self-center p-static order-2 text-center">
                            <div class="overflow-hidden pb-2">
                                <h1 class="text-dark font-weight-bold text-9 appear-animation"
                                    data-appear-animation="maskUp" data-appear-animation-delay="100">{{ $project->title ?? 'Projet' }}</h1>
                            </div>
                        </div>
                        <div class="col-md-12 align-self-center order-1">
                            <ul class="breadcrumb d-block text-center appear-animation" data-appear-animation="fadeIn"
                                data-appear-animation-delay="300">
                                <li><a href="#">Accueil</a></li>
                                <li><a href="#">Portfolio</a></li>
                                <li class="active">{{ $project->slug ?? 'Détails' }}</li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <div class="container pt-2 pb-4">

        <div class="row pb-4 mb-2">
            <div class="col-md-6 mb-4 mb-md-0 appear-animation" data-appear-animation="fadeInLeftShorter"
                data-appear-animation-delay="300">

                <div class="owl-carousel owl-theme nav-inside nav-inside-edge nav-squared nav-with-transparency nav-dark mt-3"
                    data-plugin-options="{'items': 1, 'margin': 10, 'loop': false, 'nav': true, 'dots': false}">

                    @foreach ($project->getMedia('images') as $image)
                        <div>
                            <div class="img-thumbnail border-0 border-radius-0 p-0 d-block">
                                <img src="{{ $image->getUrl() }}" class="img-fluid border-radius-0" alt="">
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- <hr class="solid my-5 appear-animation" data-appear-animation="fadeIn"
                    data-appear-animation-delay="1000">

                <div class="appear-animation" data-appear-animation="fadeInRightShorter"
                    data-appear-animation-delay="1100">
                    <strong
                        class="text-uppercase text-1 me-3 text-dark float-start position-relative top-2">Share</strong>
                    <ul class="social-icons">
                        <li class="social-icons-facebook"><a href="http://www.facebook.com/" target="_blank"
                                title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                        <li class="social-icons-x"><a href="http://www.x.com/" target="_blank" title="X"><i
                                    class="fab fa-x-twitter"></i></a></li>
                        <li class="social-icons-linkedin"><a href="http://www.linkedin.com/" target="_blank"
                                title="Linkedin"><i class="fab fa-linkedin-in"></i></a></li>
                    </ul>
                </div> --}}

            </div>
            <div class="col-md-6">
                <div class="overflow-hidden">
                    <h2 class="text-color-dark font-weight-normal text-4 mb-0 appear-animation"
                        data-appear-animation="maskUp" data-appear-animation-delay="600">
                        <strong class="font-weight-extra-bold">Déscription</strong> du projet
                    </h2>
                </div>
                <p class="appear-animation" data-appear-animation="fadeInUpShorter" data-appear-animation-delay="800">
                    {!! $project->content ?? 'Pas de déscription disponible.' !!}</p>

                <div class="overflow-hidden mt-4">
                    <h2 class="text-color-dark font-weight-normal text-4 mb-0 appear-animation"
                        data-appear-animation="maskUp" data-appear-animation-delay="1000"> 
                        <strong class="font-weight-extra-bold">Détails</strong> du projet
                    </h2>
                </div>
                <ul class="list list-icons list-primary list-borders text-2 appear-animation"
                    data-appear-animation="fadeInUpShorter" data-appear-animation-delay="1200">
                    <li>
                        <i class="fas fa-caret-right left-10"></i> <strong class="text-color-primary">Titre:</strong> {{ $project->title ?? 'N/A' }}
                    </li>
                    <li>
                        <i class="fas fa-caret-right left-10"></i> <strong class="text-color-primary">Catégorie:</strong> {{ $project->categorie ?? 'N/A' }}
                    </li>
                    <li>
                        <i class="fas fa-caret-right left-10"></i> <strong class="text-color-primary">Date:</strong> {{ $project->published_at ? $project->published_at->format('d/m/Y') : 'N/A' }}
                    </li>
                </ul>
            </div>
        </div>

    </div>

</div>
@endsection