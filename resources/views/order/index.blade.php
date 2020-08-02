@extends('layouts.cart-checkout', ['cpt' => 'order-ctrl'])

@section('title')
{{__('order.userInfo')}}
@endsection

@section('cartContent')
<div class="redirectingOverLay position-fixed w-100 h-100"
    v-show="h.d.savingOrder && h.d.paymentMethod === 'accept'">
    <div class="container d-flex h-100 position-relative">
        <div
            class="center w-100 justify-content-center align-self-center text-center">
            <div class="row">
                <div class="col-12">
                    <div class="lds-roller">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
            </div>
            <div class="row pt-2">
                <div class="col-12 text-light">
                    <h4>
                        {{__('order.pleaseWaitForRedirect')}}
                    </h4>
                    <h6>
                        {{__('order.noteOrderNotSavedUntilPaid')}}
                    </h6>
                </div>
            </div>
        </div>
    </div>
</div>

<div v-if="h.d.order.id">
    <div class="card col-12 p-0">
        <x-order-success></x-order-success>
    </div>
</div>
<div id="orderTabsWrapper" v-else>
    <ul id="orderTabsUI" class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active"
                :class="{'text-success': !h.d.hasErrors && h.d.addressChecked}"
                id="address-list-tab" href="#address-list" data-toggle="tab"
                data-height="true" aria-controls="address-list"
                aria-selected="true" role="tab">
                <i class="fas fa-address-book mx-1"></i>
                {{__('order.addressList')}}
                <i class="fas fa-check mx-1" v-if="h.d.addressChecked"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link"
                :class="{'text-success': !h.d.hasErrors && h.d.addressChecked}"
                id="paymentMethod-tab" href="#paymentMethod" data-toggle="tab"
                data-height="true" aria-controls="paymentMethod"
                aria-selected="false" role="tab">
                <i class="fas fa-money-check-alt mx-1"></i>
                {{__('order.paymentMethod')}}
                <i class="fas fa-check mx-1"
                    v-if="!h.d.hasErrors && h.d.addressChecked"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="summary-tab" href="#summary"
                data-toggle="tab" data-height="true" aria-controls="summary"
                aria-selected="false" role="tab">
                <i class="fas fa-bars mx-1"></i>
                {{__('order.summary.txt')}}
                {{-- <i class="fas fa-check mx-1"></i> --}}
            </a>
        </li>
    </ul>


    <div class="tab-content clearfix ">
        <div role="tabpanel" class="tab-pane fade active show"
            aria-labelledby="address-list-tab" id="address-list">
            @include('order.address-list')
        </div>
        <div role="tabpanel" class="tab-pane fade"
            aria-labelledby="paymentMethod-tab" id="paymentMethod">
            @include('order.paymentMethod')
        </div>
        <div role="tabpanel" class="tab-pane fade" aria-labelledby="summary-tab"
            id="summary">
            @include('order.summary')
        </div>
    </div>
</div>
@endsection