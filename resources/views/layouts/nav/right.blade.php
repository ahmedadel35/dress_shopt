<x-drop-down class="userLink" class="d-none d-md-block">
    <x-slot name="link">
        <span class="spinner-grow bg-light" :class="{'d-none': !h.d.cartLoader}"
            role="status" aria-hidden="true"></span>
        <i class="fa fas fa-cart-arrow-down carticon"></i>
        <sup class="badge badge-danger align-top">
            @{{h.d.formatNum(h.d.cart.count)}}
        </sup>
    </x-slot>

    <x-slot name="ndlink">
        <a href="#" type="button" class="btn btn-clear d-md-none"
            :class="{'mt-0 pt-0': h.d.cartLoader}"
            v-on:click.prevent.stop="h.d.openSide('cartSidebar')">
            <span class="spinner-grow bg-light"
                :class="{'d-none': !h.d.cartLoader}" role="status"
                aria-hidden="true"></span>
            <i class="fa fas fa-cart-arrow-down carticon text-light"></i>
            <sup class="badge badge-danger align-top">
                @{{h.d.formatNum(h.d.cart.count)}}
            </sup>
        </a>
    </x-slot>

    <ul class="list-unstyled px-3">
        <x-cart-list></x-cart-list>
        <li class="mt-5">
            <h5 class="font-weight-bolder">
                <span class="text-left">
                    @lang('t.index.overTotal'):
                </span>

                <span dir='ltr' class="text-right float-right text-primary"
                    style="font-size: large;">LE
                    @{{h.d.formatNum(h.d.cart.total)}}</span>
            </h5>
            <div class="form-group text-center">
                <x-link-loader href="cart/view" class="btn-primary col-5 mr-2">
                    @lang('t.index.viewC')
                </x-link-loader>
                <x-link-loader href="cart/checkout"
                    class="btn-success col-5 mr-2">
                    @lang('t.index.checkout')
                </x-link-loader>
            </div>
        </li>
    </ul>
</x-drop-down>

<x-drop-down class='userLink' class='d-none d-md-block'>
    <x-slot name="link">
        <i :class="{'animate__heartBeat animate__infinite': h.d.wishLoader}"
            class="fa fas fa-heart carticon animate__animated"></i>
        <sup class="badge badge-danger align-top">
            @{{h.d.formatNum(h.d.cart.wish.length)}}
        </sup>
    </x-slot>

    <x-slot name="ndlink">
        <a href="#" type="button" class="btn btn-clear d-md-none"
            v-on:click.prevent.stop="h.d.openSide('wishSidebar')">
            <i :class="{'animate__heartBeat animate__infinite': h.d.wishLoader}"
                class="fa fas fa-heart carticon animate__animated text-light"></i>
            <sup class="badge badge-danger align-top">
                @{{h.d.formatNum(h.d.cart.wish.length)}}
            </sup>
        </a>
    </x-slot>

    <ul class="list-unstyled px-3">
        <x-cart-list data="wish" type="wish"></x-cart-list>
    </ul>
</x-drop-down>

@auth
<li class="nav-item dropdown">
    <a id="navbarDropdown"
        class="nav-link dropdown-toggle media userLink mt-n1 pt-0 d-none d-md-flex"
        href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false" v-on:click.prevent="">
        {{-- <img src="/storage/user_profile/{{Auth::user()->img}}" width="60"
        class="mr-3 rounded" alt="{{ Auth::user()->name }} profile image"> --}}
        <x-profile-img :src="auth()->user()->img" width="50" height="50"
            :vue="false"></x-profile-img>
        <div class="media-body">
            <strong class="mt-2 ml-1 d-none d-md-block">
                {{ Auth::user()->name }}
            </strong>
        </div>
    </a>
    <div class="dropdown-menu dropdown-menu-right"
        aria-labelledby="navbarDropdown">
        <a class="dropdown-item @if (request()->route( )->named('userDash')) active @endif"
            href="{{ route('userDash') }}">
            {{__('user.dash.title')}}
        </a>
        <a class="dropdown-item @if (request()->route( )->named('userOrders')) active @endif"
            href="{{ route('userOrders') }}">
            {{__('user.order.title')}}
        </a>
        <a class="dropdown-item @if (request()->route( )->named('userAddress')) active @endif"
            href="{{ route('userAddress') }}">
            {{__('user.address.title')}}
        </a>
        <a class="dropdown-item @if (request()->route( )->named('userRates')) active @endif"
            href="{{ route('userRates') }}">
            {{__('user.rates.title')}}
        </a>
        <a class="dropdown-item @if (request()->route( )->named('userProfile')) active @endif"
            href="{{ route('userProfile') }}">
            {{__('user.profile.title')}}
        </a>
        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                         document.getElementById('logout-form45545').submit();">
            {{ __('t.Logout') }}
        </a>
        <form id="logout-form45545" action="{{ route('logout') }}" method="POST"
            style="display: none;">
            @csrf
        </form>
    </div>
</li>
@endauth