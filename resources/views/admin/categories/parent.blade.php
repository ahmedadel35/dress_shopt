<div class="card p-0">
    <div class="card-header bg-primary text-light">
        <h5>
            {{__('admin.categories.title')}}
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12 p-0">
                @if (session('success', false))
                <x-errors type="success" icon="check" :msg="session('success')">
                </x-errors>
                @endif
            </div>
            <div class="col-12 p-0">
                <x-profile-img src="h.d.prev"></x-profile-img>
                <form class="form"
                    :action="h.d.editingCat ? h.d.action : '{{route('admin.save.cat')}}'"
                    method="post"
                    class="needs-validation @error('ctitle') was-validated @enderror"
                    enctype="multipart/form-data" novalidate>
                    @csrf
                    <template v-if="h.d.editingCat">
                        @method('PATCH')
                    </template>
                    <div class="form-group">
                        <input id="ctitleInp"
                            class="form-control @error('ctitle')is-invalid @enderror"
                            type="text" name="ctitle"
                            placeholder="{{__('admin.categories.form.ctitle')}}"
                            required />
                        @error('ctitle')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input"
                                name="img" id="customFileId"
                                lang="{{app()->getLocale()}}"
                                v-on:change="h.d.previewImg($event)"
                                accept="image/jpg, image/jpeg, image/png"
                                required>
                            <label class="custom-file-label" for="customFileId">
                                {{__('admin.categories.form.imgchoose')}}
                            </label>
                        </div>
                        @error('img')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <x-submit-loader type="submit" icon="save">
                            {{__('admin.categories.form.save')}}
                        </x-submit-loader>
                    </div>
                </form>
            </div>
            <div class="col-12 p-0 cats-list">
                @forelse ($cats as $c)
                <span
                    class="badge badge-{{Arr::random(['primary', 'success', 'danger', 'warning', 'info'])}} m-1"
                    style="font-size: large">
                    <x-profile-img :vue="false"
                        :src="'storage/collection/' . $c->id . '.jpg'"
                        width="35" height="35" cls="d-inline-block mb-n2">
                    </x-profile-img>
                    <span class="align-text-top">
                        {{$c->title}}
                    </span>
                    <span class="badge badge-light">
                        {{$c->sub_cats->count()}}
                    </span>
                    <button class="btn btn-secondary btn-sm mx-1"
                        v-on:click.prevent="h.d.editCat('{{route('admin.categories.update', $c->slug)}}', '{{$c->title}}')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <form
                        action="{{route('admin.categories.delete', $c->slug)}}"
                        method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <x-submit-loader type="submit" icon="trash-alt"
                            cls="btn-secondary btn-sm mx-1">
                        </x-submit-loader>
                    </form>
                </span>
                @empty
                <x-errors :msg="__('admin.categories.empty')"></x-errors>
                @endforelse
            </div>
        </div>
    </div>
</div>