@extends('layouts.user')

@section('title')
{{__('user.order.title')}}
@endsection

@section('data')
@include('user.orders')
@endsection