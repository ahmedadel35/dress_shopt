@extends('layouts.app', ['cpt' => 'contact-us'])

@section('title')
{{__('contact.title')}}
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="/{{app()->getLocale()}}" target="_blank">
                        {{__('home.title')}}
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <i class="fas fa-comment-alt mx-1 mt-1"></i>
                    {{__('contact.title')}}
                </li>
            </ol>
        </nav>
    </div>
</div>
<div class="row">
    <h5 class="col-12">
        {{__('contact.aboutUs')}}
    </h5>
    <div class="col-12 card card-body">
        {{__('contact.whoWeAre')}}
    </div>
</div>
<div class="row mt-3">
    <div class="col-12">
        {{-- <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3452.9064810623818!2d31.358034414629312!3d30.068215024405283!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14583d45670932ed%3A0x3e21ace0ab58cbd0!2z2KzYp9mF2LnYqSDYp9mE2KfYstmH2LEg2YXYr9mK2YbYqSDZhti12LE!5e0!3m2!1sen!2seg!4v1591416735769!5m2!1sen!2seg"
            width="100%" height="450" frameborder="0" style="border:0;"
            allowfullscreen="" aria-hidden="false" tabindex="0"></iframe> --}}
    </div>
</div>
<div class="row mt-3">
    <div class="col-md-7 card card-body">
        <h4>{{__('contact.sendMessage')}}</h4>
        @include('contact.form')
    </div>
    <div class="col-md-5 card card-body">
        <h4>{{__(__('contact.title'))}}</h4>
        <ul class="list-group list-group-flush pt-4">
            @include('footer.contact')
        </ul>
        {{-- <div class="col-12 mt-2 card-body border-top">
            <h5>{{__('contact.openingHours')}}</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <span>
                        من السبت الي الخميس
                    </span>:
                    <span class="mx-3">
                        12 ظهراً - 12 منتصف اليل
                    </span>
                </li>
                <li class="list-group-item">
                    <span>
                        الجمعة
                    </span>:
                    <span class="mx-3">
                     1 ظهراً -  12 منتصف اليل 
                    </span>
                </li>
            </ul>
        </div> --}}
    </div>
</div>
@endsection