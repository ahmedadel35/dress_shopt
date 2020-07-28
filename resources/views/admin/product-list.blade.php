@extends('layouts.admin', ['cpt' => 'admin-product-list'])

@section('title')
{{__('admin.product.title')}}
@endsection

@section('data')
<div class="row">
    <div class="col-sm-6 mb-2">
        {{-- <x-link-loader href="root/products/create" icon="fa-plus">
                
        </x-link-loader> --}}
        <button type="button" class="btn btn-outline-primary" v-on:click.prevent="h.d.create('createproduct')">
            <span class="after">
                <i class="fas fa-plus mx-1"></i>
                {{__('admin.product.create')}}
            </span>
        </button>
    </div>
    <div class="col-sm-6 mb-2">
        <span>
            @lang('t.sortBy'): <div class="btn-group dropleft">
                <div class="dropdown d-inline">
                    <button
                        class="btn btn-outline-info btn-clear dropdown-toggle text-capitalize"
                        type="button" id="dropdownMenuFilterList"
                        data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <span>
                            {{__('t.show.pop')}}
                        </span>
                    </button>
                    <div class="dropdown-menu text-capitalize"
                        aria-labelledby="dropdownMenuFilterList">
                        @foreach ([
                        'pop' => __('t.show.pop'),
                        'rated' => __('t.show.rated'),
                        'lowTo' => __('t.show.lowTo'),
                        'highTo' => __('t.show.highTo')
                        ] as $k => $v)
                        <a class="dropdown-item @if(Request::get('sortBy') === $k) active @endif"
                            href="{{url()->current()}}?page={{Request::get('page', 1)}}&sortBy={{Request::get('sortBy', $k)}}">
                            {{$v}}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </span>
    </div>
</div>
<div class="row my-3">
    @forelse ($products as $p)
    @php
    $p->makeHidden('rate_avg')
    @endphp
    @include('admin.product-list.product')
    @empty
    <div class="col-12">
        <x-errors :msg="__('admin.product.empty')"></x-errors>
    </div>
    @endforelse
</div>
<div class="row">
    <div class="col-12">
        {{$products->links()}}
    </div>
</div>
<x-quickView id='adminProductModal' :withoutitem="true"></x-quickView>
@include('admin.product-form')
@endsection