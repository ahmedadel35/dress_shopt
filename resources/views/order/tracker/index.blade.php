@extends('layouts.app')

@section('title')
{{__('tracker.title')}}
@endsection

@section('content')
<div class="container">
    @if($order)
    @include('order.tracker.result')
    @else
    @if($empty)
    <x-errors :msg="__('order.tracker.sorrynotfound')"></x-errors>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-light font-weight-bold">
                    {{ __('tracker.withId') }}</div>

                <div class="card-body">
                    <form method="POST"
                        action="/{{app()->getLocale()}}/order/track"
                        class="needs-validation {{$errors->any() ? 'was-validated' : ''}}"
                        novalidate>
                        @csrf

                        <div class="input-group mb-3">
                            <div class="input-group-prepend ">
                                <span class="input-group-text"
                                    id="orderIdAddon">#</span>
                            </div>
                            <input type="text"
                                class="form-control @error('orderId') is-invalid @enderror"
                                placeholder="ad4d2s" 
                                name="orderId"
                                aria-label="orderId"
                                aria-describedby="orderIdAddon" minlength="3"
                                required autofocus>

                            @error('orderId')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"
                                    id="email">{{ __('auth.E-Mail-Address') }}</span>
                            </div>
                            <input id="email" type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required
                                autocomplete="email">

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{__('order.search')}}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection