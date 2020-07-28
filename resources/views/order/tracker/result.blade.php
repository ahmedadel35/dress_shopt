@extends('layouts.app')

@section('title')
{{__('order.trackerResult')}}
@endsection

@section('content')
<div class="row">
    <div class="col-12 col-md-6 mb-3">
        <div class="card p-0">
            <h6 class="card-header bg-primary text-light">
                {{__('order.details')}}
            </h6>
            <div class="card-body">
                @if (auth()->check() && session('completed', false))
                <x-errors type="success" icon="check"
                    :msg="session('completed')"></x-errors>
                @endif
                <table class="table">
                    <tbody>
                        <tr dir="ltr">
                            <th>#</th>
                            <td>{{$order->enc_id}}</td>
                        </tr>
                        <tr>
                            <th>{{__('order.status')}}</th>
                            <th>
                                @switch($order->status)
                                @case('processing')
                                <i
                                    class="fas fa-truck-loading mx-1 fa-2x text-primary"></i>
                                @break
                                @case('completed')
                                <i class="fas fa-check text-success fa-2x"></i>
                                @break
                                @default
                                <i
                                    class="fas fa-truck-loading mx-1 fa-2x text-primary"></i>
                                @endswitch
                                {{__('order.'.$order->status)}}
                            </th>
                        </tr>
                        <tr>
                            <th>{{__('t.index.overTotal')}}</th>
                            <th class="text-primary">LE {{$order->total}}
                            </th>
                        </tr>
                        <tr>
                            <th>{{__('orded.mail.qty')}}</th>
                            <td>{{$order->qty}}</td>
                        </tr>
                        <tr>
                            <th>{{__('order.paymentMethod')}}</th>
                            <td>
                                @if($order->paymentMethod === 'accept')
                                <span class="align-top">
                                    Accept-Pay
                                </span>
                                <span class="ml-3 d-block d-sm-inline">
                                    <i
                                        class="fab fa-cc-visa text-primary mx-1 fa-2x"></i>
                                    <i
                                        class="fab fa-cc-mastercard text-danger mx-1 fa-2x"></i>
                                </span>
                                @else
                                {{__('order.cashOnDelivery')}}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>{{__('order.mail.payStat')}}</th>
                            <th>
                                @if ($order->paymentStatus)
                                <span class="text-success">
                                    {{__('order.mail.payDone')}}
                                </span>
                                @else
                                <span class="text-danger">
                                    {{__('order.mail.payFail')}}
                                </span>
                                @endif
                            </th>
                        </tr>
                        <tr>
                            <th>-----</th>
                            <th>
                                @if (auth()->check() &&
                                Gate::allows('delivery') &&
                                ($order->status
                                === 'processing' || $order->status
                                === 'pending'))
                                <form class=""
                                    action="/{{app()->getLocale()}}/order/track/{{$order->enc_id}}/complete"
                                    method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <x-submit-loader type='submit' icon="check"
                                        cls="btn-success">
                                        {{__('order.complete')}}
                                    </x-submit-loader>
                                </form>
                                @endif
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card  p-0">
            <h6 class="card-header bg-primary text-light">
                {{__('address.details')}}
            </h6>
            <div class="card-body">
                <address>
                    <h5>{{$order->address->firstName}}
                        {{$order->address->firstName}}</h5>
                    @if (is_int($order->address->dep))
                    ({{$order->address->dep}})
                    @endif
                    {{$order->address->address}}, {{$order->address->city}}<br>
                    <strong>
                        {{$order->address->gov}} &nbsp;
                        {{$order->address->country}}
                    </strong><br>
                </address>
                <a class="mt-2" href="tel:{{$order->address->phoneNumber}}">
                    <i class="fas fa-phone-alt mx-1"></i>
                    {{$order->address->phoneNumber}}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection