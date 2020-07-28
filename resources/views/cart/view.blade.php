@extends('layouts.app', ['cpt' => 'cart'])

@section('title')
{{__('t.scart.shopp')}}
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/{{app()->getLocale()}}" target="_blank">
                        {{__('nav.home')}}
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <i class="fas fa-cart-arrow-down mt-1 mr-1"></i>
                    {{__('t.scart.shopp')}} (@{{h.d.cart.count}})
                </li>
            </ol>
        </nav>
    </div>
</div>
<div class="row list-unstyled">
    <div class="col-12 col-md-9 mb-3 card card-body">
        <div class="border-bottom py-2 d-block" v-if="h.d.cart.count > -5 && !h.d.cart.items.length">
            <div class="alert alert-danger align-content-center">
                <strong>
                    {{__('product.noItems')}}
                </strong>
            </div>
        </div>
        <x-cart-list :has-remove="false" :shopList="true">
            <div class="row mb-2">
                <strong class="col-12 col-sm-6">
                    LE @{{h.d.formatNum(c.product.saved_price)}} x@{{c.qty}}
                </strong>
                <strong class="text-primary col-12 col-sm-6">
                    <span class="text-dark">=</span> LE
                    @{{h.d.formatNum(c.qty * c.price)}}
                </strong>
            </div>
            {{-- <div class="row mb-2 d-none d-sm-block">
                <span class="col-12 col-sm-6">
                    {{__('product.size')}}: <strong class="text-primary">
                @{{c.product.sizes[c.size]}}
            </strong>
            </span>
            <span class="col-12 col-sm-6">
                {{__('product.color')}}: <strong
                    class="text-primary border px-1"
                    style="border-width: medium !important;"
                    :style="{'border-color': c.product.colors[c.color] + ' !important'}">
                    @{{c.product.colors[c.color]}}
                </strong>
            </span>
    </div> --}}
    <div class="row mb-2">
        <span class="text-capitalize col-3 pr-0">
            {{__('product.amount')}}
        </span>
        <div class="col-9 px-0">
            <number-select :count="c.product.qty"
                v-on:current-amount="c.qty = $event" :start="parseInt(c.qty)"
                :disabled="c.product.qty < 1 || h.d.cartLoader">
            </number-select>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <button type="button" class="btn btn-primary d-inline-block mb-2 mx-1"
                v-on:click.prevent.stop="h.d.updateCartItem(c, 'spinnerLoaderUpdate' + c.product_id)">
                <span
                    class="d-none spinner-border spinner-border-sm align-middle pr-1"
                    role="status" aria-hidden="true"
                    :id="'spinnerLoaderUpdate' + c.product_id"></span>
                <span class="after">
                    <i class="fas fa-cart-plus mx-1"></i>
                    {{__('product.update')}}
                </span>
            </button>
            <button type="button"
                class="btn btn-outline-danger d-inline-block mx-1 mb-2"
                v-on:click.prevent.stop="h.d.removeItemFromCart(c, 'spinnerLoaderDelete' + c.product_id)">
                <span
                    class="d-none spinner-border spinner-border-sm align-middle pr-1"
                    role="status" aria-hidden="true"
                    :id="'spinnerLoaderDelete' + c.product_id"></span>
                <span class="after">
                    <i class="fas fa-times mx-1"></i>
                    {{__('t.scart.del')}}
                </span>
            </button>
        </div>
    </div>
    </x-cart-list>
    <div class="border-bottom py-2 d-block" v-for="lod in h.d.loadingData"
        :key="Math.random() * lod">
        <content-loader width="220" height="20" primary-color="#cbcbcd"
            secondary-color="#02103b">
            <rect x="3" y="2" rx="0" ry="0" width="63" height="17"></rect>
            <rect x="78" y="2" rx="0" ry="0" width="63" height="17"></rect>
            <rect x="153" y="3" rx="0" ry="0" width="63" height="17"></rect>
        </content-loader>
    </div>
</div>
<div class="col-12 col-md-3">
    <div class="card p-0 position-sticky" style="top:10rem">
        <div class="card-header bg-primary text-light">
            <h5>
                {{__('t.index.overTotal')}}:
            </h5>
        </div>
        <div class="card-body">
            <h5 class="text-primary font-weight-bold" v-if="h.d.cart.items">
                LE @{{h.d.formatNum(h.d.cart.total)}}
            </h5>
        </div>
        <div class="card-footer">
            <x-link-loader v-bind:class="{disabled: !h.d.cart.items.length}" href="cart/checkout" class="btn-success btn-block">
                {{__('t.check.save')}}
            </x-link-loader>
        </div>
    </div>
</div>
</div>
@endsection