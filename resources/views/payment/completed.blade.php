@extends('layouts.app')

@section('title')
    @if ($err)
        {{__('payment.transactionFalid')}}
    @else
        {{__('payment.completed')}}
    @endif
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            @if ($err)
                @include('payment.errors')
            @else
                <x-order-success :vuejs="false" :order-id="$orderId"></x-order-success>
            @endif
        </div>
    </div>
@endsection

