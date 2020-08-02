 @include('layouts.nav.top_nav')
{{-- <div class="row mr-0">
    <div class="col-12 text-center bg-dark pb-1">
        <a href="/{{app()->getLocale()}}">
            <img class="rounded mx-auto d-block" width="350" src="/storage/site/logo-big.png" />
        </a>
    </div>
</div> --}}
{{-- :style="(h.d.carouselHeight && h.d.scrollTop > (h.d.carouselHeight -20)) ? 'background-color: var(--blue) !important;background: linear-gradient(120deg, #101a50, transparent);' : ''" --}}
<nav id='index-nav' class="navbar sticky-top navbar-expand navbar-dark bg-primary shadow-sm transition" :class="{'bg-transparent': (h.d.carouselHeight && h.d.scrollTop < (h.d.carouselHeight -20))}">
    <div class="container-fluid">
        <x-a class="navbar-brand ml-3" href="/">
            {{-- <img class="rounded mx-auto d-block" width="70" src="/storage/site/logo-big.png" /> --}}
            <strong class="text-center text-warning">Dress</strong>
            <small class="smallDress">
                {{__('nav.dressMess')}}
            </small>
        </x-a>
        <button type="button"
            class="navbar-brand navbar-toggler d-block d-md-none"
            v-on:click.prevent.stop="h.d.openSide('menuSidebar')">
            <span class="navbar-toggler-icon"
                aria-label="{{ __('Toggle_navigation') }}"></span>
        </button>

        <div class=" navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto d-none d-md-block">
                @include('layouts.nav.left')
            </ul>

            <div class="d-none d-sm-block">
                @include('products.index.search-form')
            </div>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                @include('layouts.nav.right')
            </ul>
        </div>
    </div>
</nav>
@isset ($cats)
@include('layouts.nav.bottom_nav')
@endisset