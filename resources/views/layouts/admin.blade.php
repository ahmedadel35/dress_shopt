@extends('layouts.user-base', ['cpt' => $cpt ?? 'admin-ctrl', 'pos' => ''])

@section('nav')
<div class="col-12 userNav px-0">
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link px-0 px-sm-2 @if (request()->route( )->named('admin.dash')) active @endif"
                href="{{route('admin.dash')}}" @if (request()->route(
                )->named('admin.dash')) v-on:click.prevent.stop="" @endif
                data-toggle="tooltip" data-placement="right"
                title="{{__('admin.dash.title')}}">
                <i class="fas fa-hashtag mx-1 fa-2x"></i>
                <span class="d-none d-sm-inline">
                    {{__('admin.dash.title')}}
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link px-0 px-sm-2 @if (request()->route( )->named('admin.products')) active @endif"
                href="{{route('admin.products')}}" @if (request()->route(
                )->named('admin.products')) v-on:click.prevent.stop="" @endif
                data-toggle="tooltip" data-placement="right"
                title="{{__('admin.products.title')}}">
                <i class="fas fa-project-diagram mx-1 fa-2x"></i>
                <span class="d-none d-sm-inline">
                    {{__('admin.products.title')}}
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link px-0 px-sm-2 @if (request()->route( )->named('admin.orders')) active @endif"
                href="{{route('admin.orders')}}" @if (request()->route(
                )->named('admin.orders')) v-on:click.prevent.stop="" @endif
                data-toggle="tooltip" data-placement="right"
                title="{{__('admin.orders.title')}}">
                <i class="fas fa-money-check-alt mx-1 fa-2x"></i>
                <span class="d-none d-sm-inline">
                    {{__('admin.orders.title')}}
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link px-0 px-sm-2 @if (request()->route( )->named('admin.users')) active @endif"
                href="{{route('admin.users')}}" @if (request()->route(
                )->named('admin.users')) v-on:click.prevent.stop="" @endif
                data-toggle="tooltip" data-placement="right"
                title="{{__('admin.users.title')}}">
                <i class="fas fa-users mx-1 fa-2x"></i>
                <span class="d-none d-sm-inline">
                    {{__('admin.users.title')}}
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link px-0 px-sm-2 @if (request()->route( )->named('admin.categories')) active @endif"
                href="{{route('admin.categories')}}" @if (request()->route(
                )->named('admin.categories')) v-on:click.prevent.stop="" @endif
                data-toggle="tooltip" data-placement="right"
                title="{{__('admin.categories.title')}}">
                <i class="fas fa-tasks mx-1 fa-2x"></i>
                <span class="d-none d-sm-inline">
                    {{__('admin.categories.title')}}
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link px-0 px-sm-2 @if (request()->route( )->named('admin.tags')) active @endif"
                href="{{route('admin.tags')}}" @if (request()->route(
                )->named('admin.tags')) v-on:click.prevent.stop="" @endif
                data-toggle="tooltip" data-placement="right"
                title="{{__('admin.tags.title')}}">
                <i class="fas fa-tags mx-1 fa-2x"></i>
                <span class="d-none d-sm-inline">
                    {{__('admin.tags.title')}}
                </span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link px-0 px-sm-2 border-0 @if (request()->route( )->named('admin.config')) active @endif"
                href="{{route('admin.config')}}" @if (request()->route(
                )->named('admin.config')) v-on:click.prevent.stop="" @endif
                data-toggle="tooltip" data-placement="right"
                title="{{__('admin.config.title')}}">
                <i class="fas fa-cogs mx-1 fa-2x"></i>
                <span class="d-none d-sm-inline">
                    {{__('admin.config.title')}}
                </span>
            </a>
        </li>
    </ul>
</div>
</div>
@endsection