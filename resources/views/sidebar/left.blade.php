<sidebar ref="menuSidebar" side="left" class="navbar-dark bg-primary small">
    <div class="container mt-n3">
        <div class="row">
            <div class="col-12">
                <x-a class="text-center" href='/'>
                    <div class="d-block">
                        @auth
                        <x-profile-img :vue="false" :src="auth()->user()->img"
                            width="120" height="120"></x-profile-img>
                        @endauth
                    </div>
                    <h5 class="mt-3 d-block text-light">
                        @guest
                        {{__('product.guest')}}
                        @else
                        {{ Auth::user()->name }}
                        @endguest
                    </h5>
                </x-a>
            </div>
            <div class="col-12 mt-3 border-bottom pb-3 text-center">
                @guest
                <a class="btn btn-sm btn-danger col-5 ml-1"
                    href="{{ route('login') }}">{{ __('t.Login') }}</a>
                @if (Route::has('register'))
                <a class="btn btn-sm btn-warning col-5 ml-1"
                    href="{{ route('register') }}">{{ __('t.Register') }}</a>
                @endif
                @else
                <x-a href='/' class="btn btn-sm btn-secondary col-5 mr-1">
                    {{ __('t.user.menu.order') }}
                </x-a>
                <button class="btn btn-sm btn-danger col-5 ml-1" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                    {{ __('t.Logout') }}
                </button>
                <form id="logout-form" action="{{ route('logout') }}"
                    method="POST" style="display: none;">
                    @csrf
                </form>
                @endguest
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-12">
                <ul class="list-group list-group-flush bg-transparent">
                    <x-a class="nav-link" href="/">
                        {{__('nav.home')}}
                    </x-a>
                    <x-collapse class="nav-link super" :title='__("nav.shop")'
                        v-on:click="h.d.addClassRemoveFromAll($event, 'nav-link.super')">
                        <ul
                            class="list-group list-group-flush bg-transparent ml-3 list-unstyled">
                            @include('sidebar.cats')
                        </ul>
                    </x-collapse>
                    <x-a class="nav-link" href="/contact">
                        {{__('nav.contact')}}
                    </x-a>
                </ul>
            </div>
        </div>
    </div>
</sidebar>

@url('*/products/*')
<sidebar ref="filterSidebar" side="left" class="navbar-dark bg-primary">
    @include('products.index.all-filters')
</sidebar>
@endurl