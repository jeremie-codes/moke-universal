<header id="header" class="header-effect-shrink" data-plugin-options="{'stickyEnabled': true, 'stickyEffect': 'shrink', 'stickyEnableOnBoxed': true, 'stickyEnableOnMobile': false, 'stickyChangeLogo': true, 'stickyStartAt': 30, 'stickyHeaderContainerHeight': 70}">
    <div class="header-body border-top-0">
        <div class="header-container container">
            <div class="header-row">
                <div class="header-column">
                    <div class="header-row">
                        <div class="header-logo">
                            <a href="{{ route('home') }}">
                                <img alt="Moke Logo" width="82" height="35" src="{{ asset('assets/images/logo.png') }}">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="header-column justify-content-end">
                    <div class="header-row">
                        <div class="header-nav header-nav-links">
                            <div
                                class="header-nav-main header-nav-main-square header-nav-main-font-alternative header-nav-main-effect-2 header-nav-main-sub-effect-1">
                                <nav class="collapse">
                                    <ul class="nav nav-pills" id="mainNav">
                                        <li>
                                            <a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Accueil</a>
                                        </li>
                                        <li>
                                            <a class="{{ request()->routeIs('shop.index') ? 'active' : '' }}" href="{{ route('shop.index') }}">Boutique</a>
                                        </li>
                                        <li>
                                            <a class="{{ request()->routeIs('portfolio.index') ? 'active' : '' }}" href="{{ route('portfolio.index') }}">Portfolio</a>
                                        </li>
                                        <li class="dropdown"> <a class="dropdown-item dropdown-toggle border border-" href="#!">FR</a>
                                            <ul class="dropdown-menu">
                                                <li class="active"> <a class="dropdown-item" href="#Fr">Français</a> </li>
                                                <li> <a class="dropdown-item" href="#PR">Portuguais</a> </li>
                                                <li> <a class="dropdown-item" href="#EN"> English </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                            <button class="btn header-btn-collapse-nav" data-bs-toggle="collapse"
                                data-bs-target=".header-nav-main nav">
                                <i class="fas fa-bars"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<hr class="mt-0 mb-5">
