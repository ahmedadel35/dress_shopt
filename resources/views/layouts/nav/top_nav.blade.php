<div class="navbar py-0 bg-dark text-light" style="z-index: 1">
    {{-- <div class="row"> --}}
        <div class="nav col-sm-6 justify-content-start pt-1 pt-md-0">
            @include('footer.social', ['cls' => 'btn-sm', 'upcls' => 'pl-4'])
        </div>
        <div class="nav col-sm-6 justify-content-end">
            @guest
            <li class="nav-item">
                <a class="nav-link text-light" href="{{route('login')}}">
                    {{ __('t.Login') }}
                </a>
            </li>
            @if (Route::has('register'))
            <li class="nav-item">
                <a class="nav-link text-light" href="{{ route('register') }}">
                    {{ __('t.Register') }}
                </a>
            </li>
            @endif
            @else
            @can('root')
            <li class="nav-item">
                <a class="nav-link text-light" href="{{route('admin.dash')}}">
                    {{ __('admin.navtitle') }}
                </a>
            </li>
            @endcan
            <li class="nav-item">
                <a class="nav-link text-light" href="{{route('userOrders')}}">
                    {{ __('t.user.menu.order') }}
                </a>
            </li>
            @endguest
            <li class="nav-item">
                @if (app()->isLocale('en'))
                <a class="nav-link text-light" rel="alternate" hreflang="ar"
                    href="/ar{{
            Str::after(url()->current(), app()->getLocale())
        }}">العربية
                </a>
                @else
                <a class="nav-link text-light" rel="alternate" hreflang="en"
                    href="/en{{
            Str::after(url()->current(), app()->getLocale())
        }}">English
                </a>
                @endif
            </li>
        </div>
    {{-- </div> --}}
</div>