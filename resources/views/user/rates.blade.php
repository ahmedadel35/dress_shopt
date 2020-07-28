@extends('layouts.user')

@section('title')
{{__('user.rates.title')}}
@endsection

@section('data')

@forelse($rates as $rate)
<div class="row">
    <div class="card card-body col-12 my-2">
        <div id="rateid{{$rate->id}}" class="media ">
            <x-profile-img :vue="false" :src="$rate->product->images[0]"
                cls="mr-3">
            </x-profile-img>
            <div class="media-body">
                <div class="mt-0 row">
                    <div class="col-8 pl-0">
                        <star-rate :percent="{{$rate->rate}}"></star-rate>
                    </div>
                    <div class="col-4 pr-0">
                        <button type="button"
                            class="btn btn-danger close px-1 bg-danger text-light"
                            v-on:click="h.d.deleteRate({{$rate->id}})">
                            <x-btn-loader id="spinner{{$rate->id}}">
                            </x-btn-loader>
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <p class="m-0">
                    {{__('user.rates.forProd')}} <strong>
                        <x-a target="_blank"
                            :href="'/product/' . $rate->product->slug">
                            {{$rate->product->title}}
                        </x-a>
                    </strong>
                    <span class="mx-2 badge badge-primary p-2 text-right">
                        <strong>{{$rate->created_at->format('d M Y')}}</strong>
                    </span>
                </p>
                <p class="text-muted">
                    {{$rate->message}}
                </p>
            </div>
        </div>
    </div>
</div>
@empty
<x-errors :msg="__('user.rates.empty')"></x-errors>
@endforelse
<div class="row">
    <div class="col-12 text-center">
        {{$rates->links()}}
    </div>
</div>
@endsection