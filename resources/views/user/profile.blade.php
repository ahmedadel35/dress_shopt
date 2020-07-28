@extends('layouts.user')

@section('title')
{{__('user.profile.title')}}
@endsection

@section('data')
<div class="row">
    <div class="col-12 col-md-8">
        <form action="{{route('userImageUpload')}}" method="post"
            class="form needs-validation mt-md-5 pt-md-5"
            :class="{'was-validated': h.d.profile.hasErr}"
            enctype="multipart/form-data"
            v-on:submit.prevent.stop="h.d.uploadImage()">
            @csrf
            <div class="form-group">
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="userImage"
                        lang="{{app()->getLocale()}}"
                        v-on:change="h.d.previewImg($event)"
                        accept="image/jpg, image/jpeg, image/png" required>
                    <label class="custom-file-label" for="userImage">
                        {{__('user.profile.chooseImg')}}
                    </label>
                    <div class="invalid-feedback"
                        :class="{'d-block': h.d.profile.hasErr}"
                        v-if="h.d.profile.errors.img[0]">
                        @{{h.d.profile.errors.img[0]}}
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary"
                    :disabled="h.d.profile.uploading || !h.d.profile.img?.files">
                    <x-btn-loader show-if="h.d.profile.uploading">
                    </x-btn-loader>
                    <i class="fas fa-upload mx-1"></i>
                    <span class="after">
                        {{__('user.profile.uploadImg')}}
                    </span>
                </button>
            </div>
        </form>
    </div>
    <div class="col-12 col-md-4 ">
        <x-profile-img src="h.d.profile.prev" width="200" height="200">
        </x-profile-img>
    </div>
</div>
<div class="row mt-1">
    @if (Session::has('success'))
    <div class="col-12">
        <x-errors type="success" :msg="'.' .session('success')" icon="check"
            cls=""></x-errors>
    </div>
    @endif
    <div class="col-12">
        <div class="card p-0">
            <h6 class="card-header bg-primary text-light">
                {{__('user.profile.changePass')}}
            </h6>
            <div class="card-body">
                <form action="{{route('updatePass')}}" method="POST"
                    class="form needs-validation {{$errors->any() ? 'was-validated' : ''}} @if(Session::has('error')) was-validated @endif"
                    novalidate>
                    @csrf
                    @method('patch')
                    <div class="form-group row">
                        <label for="newUserPass"
                            class="col-sm-2 col-form-label">{{__('user.profile.oldpass')}}</label>
                        <div class="col-sm-10">
                            <input type="password"
                                class="form-control @error('oldpass') is-invalid @enderror"
                                id="newUserPass"
                                placeholder="{{__('user.profile.oldPass')}}"
                                name="oldpass" required>

                            @error('oldpass')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            @if(Session::has('error'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ Session::get('error') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="oldUserPass"
                            class="col-sm-2 col-form-label">{{__('user.profile.pass')}}</label>
                        <div class="col-sm-10">
                            <input type="password"
                                class="form-control @error('pass') is-invalid @enderror"
                                id="oldUserPass"
                                placeholder="{{__('user.profile.pass')}}"
                                name="pass" required>

                            @error('pass')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="confUserPass"
                            class="col-sm-2 col-form-label">{{__('user.profile.conf')}}</label>
                        <div class="col-sm-10">
                            <input type="password"
                                class="form-control @error('conf') is-invalid @enderror"
                                id="confUserPass"
                                placeholder="{{__('user.profile.conf')}}"
                                name="conf" required>

                            @error('conf')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-12">
                            <button type="submit" class='btn btn-primary'
                                onclick="document.getElementById('updatepassspinner').classList.remove('d-none')">
                                <span
                                    class="d-none spinner-border spinner-border-sm align-middle pr-1 mx-1"
                                    role="status" aria-hidden="true"
                                    id="updatepassspinner"></span>
                                <span class="after">
                                    <i class="fas fa-save mx-1"></i>
                                    {{__('user.profile.update')}}
                                </span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection