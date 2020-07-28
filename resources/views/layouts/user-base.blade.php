@extends('layouts.app', ['class' => 'py-0', 'cpt' => $cpt])

@section('content')
<div class="row">
    <div
        class="col-2 col-md-3 d-sm-block bg-primary text-light py-2 pt-4 text-center nav-right">
        <div class="{{$pos ?? 'position-sticky'}}" style="top:7rem">
        <div class="row">
            <div class="col-12 overflow-hidden">
                <x-profile-img :src="$user->img" :vue="false" width="50" height="50"></x-profile-img>
            </div>
            <div class="col-12 mt-4">
                <h5>
                    {{$user->name}}
                </h5>
                <hr />
            </div>
        </div>
        <div class="row mt-2">
            @yield('nav')
        </div>
    </div>
    <div class="col-10 col-md-9 py-4">
        @yield('data')
    </div>
</div>
@endsection