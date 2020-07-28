@extends('layouts.admin')

@section('title')
{{__('admin.users.title')}}
@endsection

@section('data')
<div class="row">
    @forelse ($users as $u)
    <div class="col-12 card card-body p-2">
        <div class="media">
            <x-profile-img :vue="false" :src="$u->img" cls="mr-3">
            </x-profile-img>
            <div class="media-body">
                <h5 class="mt-0">
                    <x-a href="user/{{$u->enc_id}}">
                        {{$u->name}}
                    </x-a>
                </h5>
                @if (!empty($u->role_txt))
                <span
                    class="badge badge-@if ($u->isAdmin())primary @elseif($u->isSuper())success @elseif($u->isDelivery())danger @endif">
                    {{$u->role_txt}}
                </span><br>
                @endif
                <a href="mailto:{{$u->email}}">{{$u->email}}</a><br>
                <x-collapse :title="__('admin.users.opts')">
                    <x-link-loader :href="'user/' . $u->enc_id"
                        class="btn-primary mx-1 mb-2">
                        {{__('user.dash.title')}}
                    </x-link-loader>
                    <x-link-loader :href="'user/' . $u->enc_id . '/orders'"
                        class="btn-primary mx-1 mb-2">
                        {{__('user.order.title')}}
                    </x-link-loader>
                    <x-link-loader :href="'user/' . $u->enc_id . '/addresses'"
                        class="btn-primary mx-1 mb-2">
                        {{__('user.address.title')}}
                    </x-link-loader>
                    <x-link-loader :href="'user/' . $u->enc_id . '/rates'"
                        class="btn-primary mx-1 mb-2">
                        {{__('user.rates.title')}}
                    </x-link-loader>
                    <br />
                    <hr />
                    @if ($user->isAdmin())
                    @unless ($u->isAdmin())
                    <button class="btn btn-success mx-1 mb-2"
                        v-on:click.prevent="h.d.updateRole('toAdminspinner{{$u->enc_id}}', 'admin', '{{$u->enc_id}}')">
                        <x-btn-loader id="toAdminspinner{{$u->enc_id}}">
                        </x-btn-loader>
                        <span class="after">
                            {{__('admin.users.toadmin')}}
                        </span>
                    </button>
                    @endunless
                    @unless ($u->isSuper())
                    <button class="btn btn-info mx-1 mb-2"
                        v-on:click.prevent="h.d.updateRole('toSuperspinner{{$u->enc_id}}', 'super', '{{$u->enc_id}}')">
                        <x-btn-loader id="toSuperspinner{{$u->enc_id}}">
                        </x-btn-loader>
                        <span class="after">
                            {{__('admin.users.tosuper')}}
                        </span>
                    </button>
                    @endunless
                    @endif
                    <button class="btn btn-danger mx-1 mb-2"
                        v-on:click.prevent="h.d.updateRole('todeliveryspinner{{$u->enc_id}}', 'delivery', '{{$u->enc_id}}')">
                        <x-btn-loader id="todeliveryspinner{{$u->enc_id}}">
                        </x-btn-loader>
                        <span class="after">
                            {{__('admin.users.todelivery')}}
                        </span>
                    </button>
                    <button class="btn btn-secondary mx-1 mb-2"
                        v-on:click.prevent="h.d.updateRole('tonormalspinner{{$u->enc_id}}', 'normal', '{{$u->enc_id}}')">
                        <x-btn-loader id="tonormalspinner{{$u->enc_id}}">
                        </x-btn-loader>
                        <span class="after">
                            {{__('admin.users.tonormal')}}
                        </span>
                    </button>
                </x-collapse>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <x-errors :msg="__('admin.users.empty')"></x-errors>
    </div>
    @endforelse
</div>
<div class="row mt-3">
    <div class="col-12 justify-content-center">
        {{$users->links()}}
    </div>
</div>
@endsection