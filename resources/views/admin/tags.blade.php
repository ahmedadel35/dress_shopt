@extends('layouts.admin')

@section('title')
{{__('admin.tags.title')}}
@endsection

@section('data')
<div class="row">
    <div class="col-12">
        <div class="card p-0">
            <h5 class="card-header bg-primary text-light">
                {{__('admin.tags.form.create')}}
            </h5>
            <div class="card-body">
                @if (session('success', false))
                <x-errors type="success" icon="check" :msg="session('success')">
                </x-errors>
                @endif
                <form
                    :action="h.d.editingTag ? h.d.actionTag : '{{route('admin.tags.save')}}'"
                    method="POST"
                    class=" needs-validation {{$errors->has('title') ? 'was-validated' : ''}}"
                    novalidate>
                    @csrf
                    <template v-if="h.d.editingTag">
                        @method('PATCH')
                    </template>
                    <div class="form-group">
                        <input type="text" id="titleInp"
                            class="form-control @error('title') is-invalid @enderror"
                            name="title"
                            placeholder="{{__('admin.tags.form.title')}}"
                            required />
                        @error('title')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="col-12 form-group my-2">
                        <x-submit-loader type="submit">
                            {{__('admin.tags.form.save')}}
                        </x-submit-loader>
                    </div>
                </form>
            </div>
        </div>
        <div class="card card-body mt-4">
            <div class="row">
                @forelse ($tags as $tag)
                <span
                    class="btn btn-{{Arr::random(['primary', 'success', 'danger', 'warning', 'info'])}} m-2 col-sm-5 col-md-3">
                    {{$tag->title}}
                    <span class="badge badge-dark mx-1">
                        {{$tag->products_count}}
                    </span>
                    <button class="btn btn-dark btn-sm mx-1"
                        v-on:click.prevent="h.d.editTag('{{route('admin.tags.update', $tag->slug)}}', '{{$tag->title}}')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <form class="d-inline"
                        action="{{route('admin.tags.delete', $tag->slug)}}"
                        method="POST">
                        @csrf
                        @method('DELETE')
                        <x-submit-loader icon="trash-alt" type='submit'
                            cls="btn-dark btn-sm mx-1"></x-submit-loader>
                    </form>
                </span>
                @empty
                <x-errors :msg="__('admin.tags.empty')"></x-errors>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection