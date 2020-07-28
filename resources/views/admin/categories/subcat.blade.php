<div class="card p-0">
    <div class="card-header bg-primary text-light">
        <h5>
            {{__('admin.categories.subtitle')}}
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12 p-0">
                @if (session('successsub', false))
                <x-errors type="success" icon="check"
                    :msg="session('successsub')">
                </x-errors>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-12 p-0">
                <form class="form row"
                    :action="h.d.editingSub ? h.d.actionSub : '{{route('admin.sub.save')}}'"
                    method="POST"
                    class="needs-validation {{$errors->has('title') || $errors->has('pcat') ? 'was-validated' : ''}}"
                    novalidate>
                    @csrf
                    <template v-if="h.d.editingSub">
                        @method('PATCH')
                    </template>
                    <div class="form-group col-sm-6">
                        <select id="selectCat" name="pcat"
                            class="custom-select @error('pcat') is-invalid @enderror">
                            <option selected>
                                {{__('admin.categories.form.parent')}}
                            </option>
                            @foreach ($cats as $c)
                            <option value="{{$c->slug}}">
                                {{$c->title}}
                            </option>
                            @endforeach
                            @error('pcat')
                            <div class="invalid-feedback">
                                {{$message}}
                            </div>
                            @enderror
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <input type="text" id="subtitleInp"
                            class="form-control @error('title') is-invalid @enderror"
                            name="title"
                            placeholder="{{__('admin.categories.form.subtitle')}}"
                            required />
                        @error('title')
                        <div class="invalid-feedback">
                            {{$message}}
                        </div>
                        @enderror
                    </div>
                    <div class="col-12 form-group my-2">
                        <x-submit-loader type="submit">
                            {{__('admin.categories.form.savesub')}}
                        </x-submit-loader>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12 p-0">
                @foreach ($cats as $c)
                <x-collapse :title="$c->title"
                    class="btn btn-outline-primary m-2">
                    <div class="list-group list-group-flush">
                        @foreach ($c->sub_cats as $sub)
                        <a href="/{{app()->getLocale()}}/products/{{$sub->slug}}"
                            target="_blank"
                            class="list-group-item list-group-item-action text-primary">
                            {{$sub->title}}
                            <span class="float-right">
                                <button type="button"
                                    class="btn btn-info btn-sm mx-1"
                                    v-on:click.prevent="h.d.editSub('{{route('admin.sub.update', $sub->slug)}}', '{{$sub->title}}', '{{$c->slug}}')">
                                    <span class="after">
                                        <i class="fas fa-edit"></i>
                                    </span>
                                </button>
                                <form class='d-inline'
                                    action="{{route('admin.sub.delete', $sub->slug)}}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <x-submit-loader type="submit"
                                        cls="btn-danger btn-sm mx-1"
                                        icon="trash-alt">
                                    </x-submit-loader>
                                </form>
                            </span>
                        </a>
                        @endforeach
                    </div>
                </x-collapse>
                @endforeach
            </div>
        </div>
    </div>
</div>