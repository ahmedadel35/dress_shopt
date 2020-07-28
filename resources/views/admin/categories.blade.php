@extends('layouts.admin')

@section('title')
{{__('admin.categories.title')}}
@endsection

@section('data')
<div class="row">
    <div class="col-12">
        @include('admin.categories.parent')
    </div>
</div>
<div class="row">
    <div class="col-12">
        @include('admin.categories.subcat')
    </div>
</div>
@endsection