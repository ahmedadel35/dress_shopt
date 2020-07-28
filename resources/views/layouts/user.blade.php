@extends('layouts.user-base', ['cpt' => 'user-ctrl'])

@section('nav')
<div class="col-12 userNav px-0">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link px-0 px-sm-2 @if (request()->route( )->named('userDash')) active @endif"
                href="{{route('userDash')}}" @if (request()->route(
                )->named('userDash')) v-on:click.prevent.stop="" @endif
                data-toggle="tooltip" data-placement="right"
                title="{{__('user.dash.title')}}">
                <i class="fas fa-user mx-1 fa-2x"></i>
                <span class="d-none d-sm-inline">
                    {{__('user.dash.title')}}
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link px-0 px-sm-2 @if (request()->route( )->named('userOrders')) active @endif"
                href="{{route('userOrders')}}" @if (request()->route(
                )->named('userOrders')) v-on:click.prevent.stop="" @endif
                data-toggle="tooltip" data-placement="right"
                title="{{__('user.order.title')}}">
                <i class="fas fa-money-check-alt mx-1 fa-2x"></i>
                <span class="d-none d-sm-inline">
                    {{__('user.order.title')}}
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link px-sm-0 px-sm-2 text-left text-sm-center @if (request()->route( )->named('userAddress')) active @endif"
                href="{{route('userAddress')}}" @if (request()->route(
                )->named('userAddress')) v-on:click.prevent.stop="" @endif
                data-toggle="tooltip" data-placement="right"
                title="{{__('user.address.title')}}">
                <i class="fas fa-address-card mx-1 fa-2x"></i>
                <span class="d-none d-sm-inline">
                    {{__('user.address.title')}}
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link px-0 px-sm-2 @if (request()->route( )->named('userRates')) active @endif"
                href="{{route('userRates')}}" @if (request()->route(
                )->named('userRates')) v-on:click.prevent.stop="" @endif
                data-toggle="tooltip" data-placement="right"
                title="{{__('user.rates.title')}}">
                <i class="fas fa-star mx-1 fa-2x"></i>
                <span class="d-none d-sm-inline">
                    {{__('user.rates.title')}}
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link px-0 px-sm-2 border-0 @if (request()->route( )->named('userProfile')) active @endif"
                href="{{route('userProfile')}}" @if (request()->route(
                )->named('userProfile')) v-on:click.prevent.stop="" @endif
                data-toggle="tooltip" data-placement="right"
                title="{{__('user.profile.title')}}">
                <i class="fas fa-cogs mx-1 fa-2x"></i>
                <span class="d-none d-sm-inline">
                    {{__('user.profile.title')}}
                </span>
            </a>
        </li>
    </ul>
</div>
</div>
@endsection