@extends('layouts.admin')

@section('title')
    {{__('admin.orders.title')}}
@endsection

@section('data')
@include('user.orders')
@endsection