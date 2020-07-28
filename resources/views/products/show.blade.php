@extends('layouts.app', ['cpt' => 'product-show'])

@section('title')
    {{$product->title}}
@endsection

@section('content')
@js(slug, $slug)
@js(pid, $product->id)
<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/{{app()->getLocale()}}" target="_blank">
                        {{__('nav.home')}}
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a
                        href="/{{app()->getLocale()}}/products/{{$product->category_slug}}">
                        {{__(str_replace('-', ' ', $product->category_slug))}}
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{$product->title}}
                </li>
            </ol>
        </nav>
    </div>
</div>
 <div class="row mt-2">
    <div class="col-12">
        <strong class="badge badge-primary m-1" v-for="tag in h.d.product.tags"
            :key="tag.id">
            @{{tag.title}}
        </strong>
    </div>
</div>
<div class="row my-4">
    <div class="col-12 col-md-6 mb-4">
        <img-slider ref="slider" :save="{{$product->save}}" :no-load="true"
            :images="{{$images}}"></img-slider>
    </div>
    <div class="col-12 col-md-6">
        <h4 class="pl-3">
            {{$product->title}}
        </h4>
        <div class="row p-3">
            <strong class="text-primary col-6 col-sm-4">
                LE {{number_format($product->saved_price, 2)}}
            </strong>
            @if ($product->save)
            <del class="text-muted col-6 col-sm-4 mb-2">
                LE {{number_format($product->price, 2)}}
            </del>
            <span class="text-muted col-12 col-sm-4">
                {{__('t.youSave')}} LE
                {{number_format($product->price - $product->saved_price, 2)}}
            </span>
            @endif
        </div>
        <p class="text-secondary pl-3 mt-2">
            {{$product->mini_info}}
        </p>
        <div class='col-12'>
            @include('products.show.cartOpt')
        </div>
    </div>
</div>
<div class="row my-4">
    <div class="col-12 text-center">
        <x-social class="btn-clear hoverPrimary justify-content-center"
            product='product'>
            <li class="nav-item">
                <a class="nav-link btn btn-clear"
                    :class="{active: h.d.inWish,
                                    'animate__animated animate__heartBeat animate__infinite': h.d.pItem.wishing,
                                disabled: h.d.wishLoader}"
                    v-on:click.prevent="h.d.addToWish('pItem')" href="#">
                    <i class="fas fa-heart"></i>
                </a>
            </li>
        </x-social>
    </div>
</div>
<div class="row my-3 px-2">
    <x-prod-slid key="feat" load-more="loadFeaturedProds" modal="openModal" :title="__('product.featured')"></x-prod-slid>
</div>
<div class="row my-4">
    <div class="col-12 col-md-9 p-0 card card-body bg-transparen"
        style="border-right: none;">
        @include('products.show.tabs')
    </div>
</div>
<div class="row my-3 px-2">
    <x-prod-slid key="related" load-more="loadRelatedProds" modal="openModal" :title="__('product.related')">
    </x-prod-slid>
</div>
<x-quickView id="productShowModal"></x-quickView>
@endsection