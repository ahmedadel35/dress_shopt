<ul class="list-group list-group-flush">
    @guest
    <li class="list-group-item bg-dark border-0">
        <a class="text-light" href="{{ route('login') }}">
            {{ __('t.Login') }}
        </a>
    </li>
    @endguest
    <li class="list-group-item bg-dark @auth border-0 @else border-secondary @endauth">
        <x-a class="text-light" href="order/track">
            {{ __('footer.trackOrder') }}
        </x-a>
    </li>
    <li class="list-group-item bg-dark border-secondary">
        <x-a href="cart/view" class="text-light">
            {{ __('t.scart.shopp') }}
        </x-a>
    </li>
    <li class="list-group-item bg-dark border-secondary">
        <a class="text-light" href="{{route('userProfile')}}">
            {{ __('t.user.menu.profile') }}
        </a>
    </li>
    <li class="list-group-item bg-dark border-secondary">
        <a class="text-light" href="{{route('userOrders')}}">
            {{ __('t.user.menu.order') }}
        </a>
    </li>
    <li class="list-group-item bg-dark border-secondary">
        <a class="text-light" href="{{route('userAddress')}}">
            {{ __('user.addresses.title') }}
        </a>
    </li>
</ul>