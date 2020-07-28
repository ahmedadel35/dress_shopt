@extends('layouts.cart-checkout')

@section('title')
{{__('cart.checkout')}}
@endsection

@section('cartContent')
<div class="row">
    @if(session()->has('productOut'))
    @include ('cart.checkout.stock-err')
    @endif
    @if (session()->has('productAmount'))
    @include ('cart.checkout.qty-err')
    @endif
    <div v-if="!h.d.cart.items.length && !h.d.cartLoader" class="col-12">
        <x-errors :msg="__('cart.emptyErr')" cls="px-4 py-3" type="warning"
            icon="exclamation"></x-errors>
    </div>
</div>
<div class="row mt-4" vif="h.d.cart.items.length">
    <div class="col-12">
        <x-link-loader href="order/user-info"
            v-if="h.d.cart.items.length && !h.d.cartLoader"
            class="btn-primary float-left" icon="fa-address-book">
            {{__('cart.continueToChooseAddress')}}
        </x-link-loader>
        <a v-else href="#" class="btn btn-primary float-left disabled">
            <i class="fa fa-address-book mx-1"></i>
            {{__('cart.continueToChooseAddress')}}
        </a>
        <x-link-loader href='cart/view' class="btn-outline-danger float-right">
            {{__('cart.backToCart')}}
        </x-link-loader>
    </div>
</div>
@endsection