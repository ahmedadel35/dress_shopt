@extends('layouts.admin')

@section('title')
{{__('admin.config.title')}}
@endsection

@section('data')
<div class='row'>
    <div class="col-12">
        <div class="card p-0">
            <h5 class="card-header bg-primary text-light">
                {{__('admin.config.homeCarsoul')}}
            </h5>
            <div class="card-body">
                @if (session('carsuccess', false))
                <x-errors type="success" icon="check"
                    :msg="session('carsuccess')">
                </x-errors>
                @endif
                <div class="mx-auto row" v-for="p in h.d.prevArr"
                    :key="p + Math.random()" style="display: inline-block;">
                    <x-profile-img src="p"></x-profile-img>
                </div>
                <form action="{{route('admin.config.carsoul')}}" method="POST"
                    class="form needs-validation @error('slider') was-validated @enderror"
                    enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="form-group">
                        <div
                            class="custom-file @error('slider') is-invalid @enderror">
                            <input id="img" type="file"
                                class="custom-file-input" name="slider"
                                lang="{{app()->getLocale()}}"
                                v-on:change="h.d.previewImgArr($event)"
                                accept="image/jpg, image/jpeg, image/png"
                                required>
                            <label class="custom-file-label" for="img">
                                {{__('admin.config.form.chooseImg')}}
                            </label>
                        </div>
                        @error('slider')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="text" name="title"
                            class="form-control @error('title') is-invalid @enderror"
                            id="title"
                            placeholder="{{__('admin.config.form.title')}}"
                            required>
                        @error('title')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <input type="url" name="url"
                            class="form-control @error('url') is-invalid @enderror"
                            id="url"
                            placeholder="{{__('admin.config.form.url')}}"
                            required>
                        @error('url')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <x-submit-loader type="submit" icon="save">
                            {{__('admin.config.form.save')}}
                        </x-submit-loader>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row my-2">
    <div class="col-12">
        <div class="row">
            @foreach ($sliders as $s)
            <div class="card col-sm-6 mb-2 bg-dark text-light p-0">
                <img src="{{$s->img}}" class="card-img" />
                <div class="card-img-overlay"
                    style="background-color: rgba(0, 0, 0, 0.5)">
                    <h4 class="card-title mt-5">
                        {{$s->title}}<br>
                        <a href="{{$s->url}}" target="_blank">{{$s->url}}</a>
                        <br>
                        <form class=""
                            action="{{route('admin.config.carsoul.delete', $s->id)}}"
                            method="POST">
                            @csrf
                            @method('DELETE')
                            <x-submit-loader type='submit' icon="trash-alt"
                                cls="btn-danger">
                            </x-submit-loader>
                        </form>
                    </h4>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection