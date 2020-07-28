@extends('layouts.app', ['cpt' => 'product-list'])

@section('content')

<div class="">
    <div class="row my-3">
        @include('products.index.head')
    </div>
    <div class="row">
        <div class="col-12 col-md-3">
            @include('products.index.filter')
        </div>
        <div class="col-12 col-md-9">
            <div class="row">
                <div class="col-12" v-if="h.d.empty">
                    <div class="alert alert-danger w-75 mx-auto">
                        @lang('t.show.noPros')
                    </div>
                </div>
                @include('product.index.list')
            </div>
        </div>
    </div>
</div>
<x-quickView></x-quickView>
@endsection