<div class="loading-overlay">
    <div class="bounce-loader">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
    </div>
</div>

<div class="mobile-menu-overlay"></div>
<!-- End .mobil-menu-overlay -->

<div class="mobile-menu-container">
    <div class="mobile-menu-wrapper">
        <span class="mobile-menu-close"><i class="fa fa-times"></i></span>
        <nav class="mobile-nav">
            <ul class="mobile-menu">
                <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
                    <a href="{{ route('home') }}">Accueil</a>
                </li>
                <li class="{{ request()->routeIs('shop.*') ? 'active' : '' }}">
                    <a href="{{ route('shop.index') }}">Boutique</a>
                </li>
                <li class="{{ request()->routeIs('portfolio.*') ? 'active' : '' }}">
                    <a href="{{ route('portfolio.index') }}">Portfolio</a>
                </li>
                <li class="{{ request()->routeIs('cart.*') ? 'active' : '' }}">
                    <a href="{{ route('cart.index') }}">Panier</a>
                </li>
                @auth
                    <li class="{{ request()->routeIs('customer.orders') ? 'active' : '' }}">
                        <a href="{{ route('customer.orders') }}">Mes commandes</a>
                    </li>
                @endauth
            </ul>
        </nav>
        <!-- End .mobile-nav -->

        <form class="search-wrapper mb-2" action="{{ route('shop.index') }}" method="get">
            <input type="search" class="form-control mb-0" name="q" placeholder="Recherche..." required />
            <button class="btn icon-search text-white bg-transparent p-0" type="submit" title="Rechercher"></button>
        </form>

        <div class="social-icons">
            <a href="#" class="social-icon social-facebook icon-facebook" target="_blank">
            </a>
            <a href="#" class="social-icon social-twitter icon-twitter" target="_blank">
            </a>
            <a href="#" class="social-icon social-instagram icon-instagram" target="_blank">
            </a>
        </div>
    </div>
    <!-- End .mobile-menu-wrapper -->
</div>
<!-- End .mobile-menu-container -->

<a id="scroll-top" href="#top" title="Top" role="button"><i class="icon-angle-up"></i></a>
