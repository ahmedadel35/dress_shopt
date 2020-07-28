@extends('layouts.app', ['cpt' => $cpt ?? 'cart'])

@section('title')
{{__('cart.checkout')}}
@endsection

@section('content')
<div class="row">
    <div class="d-none d-md-block col-md-6">
        <div class="card card-body list-unstyled">
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
            </li>
        </div>
    </div>
    <div class="col-md-6">
        @yield('cartContent')
    </div>
</div>

@endsection