@extends('layouts.app', ['class' => 'pb-4', 'cpt' => 'home'])

@section('title')
    {{__('home.title')}}   
@endsection

@section('content')
<div class="row bg-dark">
    @include('home.carsoul')
</div>
<div class="row py-4">
    @include('home.collections-card')
</div>
<div class="row pb-4">
    <x-prod-slid key="feat" load-more="loadFeaturedProds" modal="openModal"
        :title="__('product.featured')" cls="col-12"
        headerCls="bg-primary text-light text-center"></x-prod-slid>
</div>
<div class="row pb-4 pt-2 ">
    <div class="col-12 pb-2 mx-0">
        <h5 class="card-header bg-primary text-light text-center">
            {{__('home.latest')}}
        </h5>
    </div>
    @include('product.index.list', ['mdWidth' => 3])
</div>
<x-quickView id="homeProductModal"></x-quickView>
@endsection