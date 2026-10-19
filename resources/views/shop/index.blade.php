@extends('layouts::app')

@section('title', 'Boutique')

@section('body')
<main class="main">
    <div class="category-banner-container bg-gray">
        <div class="category-banner banner text-uppercase"
            style="background: no-repeat 60%/cover url('{{ asset('assets/images/banners/banner-top.jpg') }}');">
            <div class="container position-relative">
                <div class="row">
                    <div class="pl-lg-5 pb-5 pb-md-0 col-sm-7 col-lg-6">
                        <h3>Boutique</h3>
                        <span>Découvrez notre sélection de produits</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="icon-home"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Boutique</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-12 main-content">
                <div class="toolbox">
                    <div class="toolbox-left">
                        <p class="m-0">{{ $products->total() }} produit(s)</p>
                    </div>
                </div>

                @if ($products->isNotEmpty())
                    <div class="row">
                        @foreach ($products as $product)
                            <div class="col-6 col-sm-4 col-lg-3">
                                @include('components.product-card', ['product' => $product])
                            </div>
                        @endforeach
                    </div>

                    <nav class="toolbox toolbox-pagination" aria-label="Pagination des produits">
                        {{ $products->links() }}
                    </nav>
                @else
                    <p class="py-5 text-center">Aucun produit n’est disponible pour le moment.</p>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection
