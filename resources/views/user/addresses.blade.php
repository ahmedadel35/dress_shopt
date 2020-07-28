@extends('layouts.user')

@section('title')
{{__('user.address.title')}}
@endsection

@section('data')
<div class="row">
    <div class="col-12">
        <h4>
            {{__('user.address.title')}}
            <button class="btn btn-outline-primary"
                v-on:click.prevent="h.d.createAddress()">
                <span class="after">
                    {{__('user.address.create')}}
                </span>
            </button>
        </h4>

    </div>
</div>
<div id='address-list-row' class="row mt-2">

    @forelse ($addresses as $add)
    <div id="address{{$add->id}}" class="col-md-6 mb-3">
        <div class="card card-body">
            <x-address :address="$add"></x-address>
            <div class="d-block pt-2">
                <button class="btn btn-outline-info mx-1 mb-2"
                    v-on:click.prevent="h.d.editAddress('{{$add->toJson()}}')">
                    <i class="fas fa-edit mx-1"></i>
                    <span class="after">
                        {{__('order.address.edit')}}
                    </span>
                </button>
                <button class="btn btn-outline-danger mx-1 mb-2"
                    v-on:click.prevent="h.d.deleteAddress({{$add->id}})">
                    <x-btn-loader id="spinnerDelete{{$add->id}}"></x-btn-loader>
                    <i class="fas fa-times mx-1"></i>
                    <span class="after">
                        {{__('order.address.delete')}}
                    </span>
                </button>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12" id='emptyMess'>
        <x-errors :msg="__('user.address.empty')"></x-errors>
    </div>
    @endforelse
</div>
@include ('user.address.form-modal')
@endsection