<header class="header">
    <div class="header-top">
        <div class="container">
            <div class="header-left d-none d-sm-block">
                <p class="top-message text-uppercase">FREE Returns. Standard Shipping Orders $99+</p>
            </div>
            <!-- End .header-left -->

            <div class="header-right header-dropdowns ml-0 ml-sm-auto w-sm-100">
                <div class="header-dropdown">
                    <a href="#"><i class="flag-fr flag"></i>FRA</a>
                    <div class="header-menu">
                        <ul>
                        <li><a href="#"><i class="flag-fr flag mr-2"></i>Français</a></li>
                        <li><a href="#"><i class="flag-us flag mr-2"></i>English</a></li>
                        <li><a href="#"><i class="flag-ao flag mr-2"></i>Português</a></li>
                        </ul>
                    </div>
                    <!-- End .header-menu -->
                </div>
                <!-- End .header-dropown -->

                <span class="separator"></span>

                <div class="social-icons">
                    <a href="#" class="social-icon social-facebook icon-facebook" target="_blank"></a>
                    <a href="#" class="social-icon social-twitter icon-twitter" target="_blank"></a>
                    <a href="#" class="social-icon social-instagram icon-instagram" target="_blank"></a>
                </div>
                <!-- End .social-icons -->
            </div>
            <!-- End .header-right -->
        </div>
        <!-- End .container -->
    </div>
    <!-- End .header-top -->

    <div class="header-middle sticky-header" data-sticky-options="{'mobile': true}">
        <div class="container">
            <div class="header-left col-lg-2 w-auto pl-0">
                <button class="mobile-menu-toggler text-danger mr-2" type="button">
                    <i class="fas fa-bars"></i>
                </button>
                <a href="{{ route('home') }}" class="logo">
                    <img src="{{ asset('assets/images/logo.png') }}" width="111" height="44" alt="Moke Logo">
                </a>
            </div>
            <!-- End .header-left -->

            <div class="header-right w-lg-max">
                <div
                    class="header-icon header-search header-search-inline header-search-category w-lg-max text-right mt-0">
                    <a href="#" class="search-toggle" role="button"><i class="icon-search-3"></i></a>
                    <form action="{{ route('shop.index') }}" method="get">
                        <div class="header-search-wrapper border border-secondary">
                            <input type="search" class="form-control" name="q" id="q"  placeholder="Recherche..." required>
                            <div class="select-custom">
                                <select id="cat" name="cat">
                                <option value="">Tout</option>
                                <option value="book">Livres</option>
                                <option value="comic">Bande dessinnée</option>
                                <option value="clothing">Vêtements</option>
                                </select>
                            </div>
                            <!-- End .select-custom -->
                            <button class="btn icon-magnifier p-0" title="search" type="submit"></button>
                        </div>
                        <!-- End .header-search-wrapper -->
                    </form>
                </div>
                <!-- End .header-search -->

                <div class="header-contact d-none d-lg-flex pl-4 pr-4">
                    <img alt="phone" src="assets/images/phone.png" width="30" height="30" class="pb-1">
                    <h6><span>Appelez-nous maintenant</span><a href="tel:{{ $phone }}" class="text-dark font1">{{ $phone }}</a></h6>
                </div>

            <div class="dropdown cart-dropdown d-md-none">
                <a href="{{ route('cart.index') }}" title="Cart" class="dropdown-toggle dropdown-arrow cart-toggle" role="button"  aria-haspopup="true" aria-expanded="false" data-display="static">
                    <i class="minicart-icon"></i>
                    <span class="cart-count badge-circle" data-cart-count>{{ $cartCount }}</span>
                </a>
            </div>
            <!-- End .dropdown -->
            </div>
            <!-- End .header-right -->
        </div>
        <!-- End .container -->
    </div>
    <!-- End .header-middle -->

    <div class="header-bottom sticky-header d-none d-lg-block" data-sticky-options="{'mobile': false}">
        <div class="container">
            <nav class="main-nav w-100">
                <ul class="menu">
                    <li class="{{ request()->routeIs('home') ? 'active' : '' }}"><a  href="{{ route('home') }}">Accueil</a></li>
                    <li class="{{ request()->routeIs('shop.index') ? 'active' : '' }}"><a href="{{ route('shop.index') }}">Boutique</a></li>
                    <li class="{{ request()->routeIs('portfolio.index') ? 'active' : '' }}"><a href="{{ route('portfolio.index') }}">Portfolio</a></li>
                    {{-- <li><a href="">Contact</a></li> --}}
                    <li class="float-right">
                    {{-- <a href="#" rel="noopener" class="badge badge-dark px-3 text-white">News !</a> --}}
                    <div class="dropdown cart-dropdown">
                        <a href="{{ route('cart.index') }}" title="Cart" class="dropdown-toggle dropdown-arrow cart-toggle" role="button"  aria-haspopup="true" aria-expanded="false" data-display="static">
                            <i class="minicart-icon"></i>
                            <span class="cart-count badge-circle" data-cart-count>{{ $cartCount }}</span>
                        </a>
                        <!-- End .dropdown-menu -->
                    </div>
                </li>
                </ul>
            </nav>
        </div>
        <!-- End .container -->
    </div>
    <!-- End .header-bottom -->
</header>
<!-- End .header -->
