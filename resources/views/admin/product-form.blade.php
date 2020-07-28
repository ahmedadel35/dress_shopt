<x-modal id="createproduct">
    <x-slot name="header">
        {{__('product.form.createTitle')}}
    </x-slot>

    <form class="form col-12 needs-validation"
        :class="{'was-validated': h.d.form.hasErrors}" method="POST">
        <div class="form-group row">
            <label for="title"
                class="col-sm-2 col-form-label">{{__('product.form.title')}}</label>
            <div class="col-sm-10">
                <input type="text" name="title" class="form-control"
                    :class="{'is-invalid': h.d.errors.title}" id="title"
                    v-model.trim="h.d.form.title"
                    placeholder="{{__('product.form.title')}}" required
                    minlength="5">
                <span class="invalid-feedback" role="alert"
                    v-if="h.d.errors.title">
                    <strong>@{{h.d.errors.title[0]}}</strong>
                </span>
            </div>
        </div>
        <div class="form-group row">
            <label for="price"
                class="col-sm-2 col-form-label">{{__('product.form.price')}}</label>
            <div class="col-sm-10">
                <input type="text" name="price" class="form-control"
                    :class="{'is-invalid': h.d.errors.price}" id="price"
                    v-model="h.d.form.price"
                    placeholder="{{__('product.form.price')}}... LE 1" required
                    min="1">
                <span class="invalid-feedback" role="alert"
                    v-if="h.d.errors.price">
                    <strong>@{{h.d.errors.price[0]}}</strong>
                </span>
            </div>
        </div>
        <div class="form-group row">
            <label for="save"
                class="col-sm-2 col-form-label">{{__('product.form.save')}}</label>
            <div class="col-sm-10">
                <input type="number" name="save" class="form-control"
                    :class="{'is-invalid': h.d.errors.save}" id="save"
                    v-model.number="h.d.form.save"
                    placeholder="{{__('product.form.save')}}" required min="0"
                    max="100">
                <span class="invalid-feedback" role="alert"
                    v-if="h.d.errors.save">
                    <strong>@{{h.d.errors.save[0]}}</strong>
                </span>
            </div>
        </div>
        <div class="form-group row">
            <label for="qty"
                class="col-sm-2 col-form-label">{{__('product.form.qty')}}</label>
            <div class="col-sm-10">
                <input type="number" name="qty" class="form-control"
                    :class="{'is-invalid': h.d.errors.qty}" id="qty"
                    v-model.number="h.d.form.qty"
                    placeholder="{{__('product.form.qty')}}" required min="1"
                    step="1">
                <span class="invalid-feedback" role="alert"
                    v-if="h.d.errors.qty">
                    <strong>@{{h.d.errors.qty[0]}}</strong>
                </span>
            </div>
        </div>
        <div class="form-group row">
            <label for="colors"
                class="col-sm-2 col-form-label">{{__('product.form.colors')}}</label>
            <div class="col-sm-10">
                <vue-tags-input v-model="h.d.form.color" :tags="h.d.form.colors"
                    :class="{'is-invalid': h.d.errors.colors}" id="colors"
                    v-on:tags-changed="newTags => h.d.form.colors = newTags"
                    placeholder="{{__('product.form.colors')}}" required />
                <span class="invalid-feedback" role="alert"
                    v-if="h.d.errors.colors">
                    <strong>@{{h.d.errors.colors[0]}}</strong>
                </span>
            </div>
        </div>
        <div class="form-group row">
            <label for="sizes"
                class="col-sm-2 col-form-label">{{__('product.form.sizes')}}</label>
            <div class="col-sm-10">
                <vue-tags-input v-model="h.d.form.size" :tags="h.d.form.sizes"
                    :class="{'is-invalid': h.d.errors.sizes}" id="sizes"
                    v-on:tags-changed="newTags => h.d.form.sizes = newTags"
                    placeholder="{{__('product.form.sizes')}}" required />
                <span class="invalid-feedback" role="alert"
                    v-if="h.d.errors.sizes">
                    <strong>@{{h.d.errors.sizes[0]}}</strong>
                </span>
            </div>
        </div>
        <div class="form-group row">
            <label for="info"
                class="col-sm-2 col-form-label">{{__('product.form.info')}}</label>
            <div class="col-sm-10">
                <textarea name="info" class="form-control"
                    :class="{'is-invalid': h.d.errors.info}" id="info"
                    v-model="h.d.form.info"
                    placeholder="{{__('product.form.info')}}" required
                    minlength="5"></textarea>
                <span class="invalid-feedback" role="alert"
                    v-if="h.d.errors.info">
                    <strong>@{{h.d.errors.info[0]}}</strong>
                </span>
            </div>
        </div>
        <div class="form-group row">
            <label for="category_slug"
                class="col-sm-2 col-form-label">{{__('product.form.category_slug')}}</label>
            <div class="col-sm-10">
                <select type="text" name="category_slug" class="form-control"
                    :class="{'is-invalid': h.d.errors.category_slug}"
                    id="category_slug" v-model="h.d.form.category_slug"
                    placeholder="{{__('product.form.category_slug')}}" required>
                    @js(cats, $cats)
                    <optgroup v-for="c in h.d.cats" :key="c.title"
                        :label="c.title">
                        <option v-for="sub in c.sub_cats" :key="sub.title"
                            :value="sub.slug">@{{sub.title}}</option>
                    </optgroup>
                </select>
                <span class="invalid-feedback" role="alert"
                    v-if="h.d.errors.category_slug">
                    <strong>@{{h.d.errors.category_slug[0]}}</strong>
                </span>
            </div>
        </div>
        <div class="form-group row">
            <label for="tags"
                class="col-sm-2 col-form-label">{{__('product.form.tags')}}</label>
            <div class="col-sm-10">
                @js(tags, $tags)
                <vue-tags-input v-model="h.d.form.tag" :tags="h.d.form.tags"
                    :class="{'is-invalid': h.d.errors.tags}" id="tags"
                    :autocomplete-items="h.d.filteredItems()"
                    :add-only-from-autocomplete="true"
                    v-on:tags-changed="newTags => h.d.form.tags = newTags"
                    placeholder="{{__('product.form.tags')}}" required />
                <span class="invalid-feedback" role="alert"
                    v-if="h.d.errors.tags">
                    <strong>@{{h.d.errors.tags[0]}}</strong>
                </span>
            </div>
        </div>
        {{-- <div class="form-group row">
            <label for="more"
                class="col-sm-2 col-form-label">{{__('product.form.more')}}</label>
        <div class="col-sm-10">
            <textarea name="more" class="form-control"
                :class="{'is-invalid': h.d.errors.more}" id="more"
                v-model="h.d.form.more"
                placeholder="{{__('product.form.more')}}" required minlength="5"
                rows="4"></textarea>
            <span class="invalid-feedback" role="alert" v-if="h.d.errors.more">
                <strong>@{{h.d.errors.more[0]}}</strong>
            </span>
        </div>
        </div> --}}
        <div class="form-group row">
            <label for="keys"
                class="col-sm-2 col-form-label">{{__('product.form.keys')}}</label>
            <div class="col-sm-10">
                <button class="btn btn-outline-primary"
                    v-on:click.prevent="() => {h.d.attr.keys.push('');h.d.attr.vals.push('')}">
                    <span class="after">
                        <i class="fas fa-plus"></i>
                    </span>
                </button>
                <div class="row my-2" v-for="(key, kkinx) in h.d.attr.keys"
                    :key="kkinx">
                    <input type="text" class="form-control col-6"
                        v-model="h.d.attr.keys[kkinx]"
                        placeholder="{{__('product.form.attr.key')}}" />
                    <input type="text" class="form-control col-6"
                        v-model="h.d.attr.vals[kkinx]"
                        placeholder="{{__('product.form.attr.val')}}" />
                </div>
                <span class="invalid-feedback" role="alert"
                    v-if="h.d.errors.keys">
                    <strong>@{{h.d.errors.keys[0]}}</strong>
                </span>
            </div>
        </div>
        <div class="form-group">
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="productImage"
                    lang="{{app()->getLocale()}}"
                    v-on:change="h.d.previewImg($event)"
                    accept="image/jpg, image/jpeg, image/png" multiple required>
                <label class="custom-file-label" for="userImage">
                    {{__('product.form.chooseImg')}}
                </label>
                <div class="invalid-feedback" v-if="h.d.errors.images">
                    @{{h.d.errors.images[0]}}
                </div>
            </div>
        </div>
    </form>
    <div class="mx-auto row" v-for="p in h.d.prev" :key="p + Math.random()"
        style="display: inline-block;">
        <x-profile-img src="p"></x-profile-img>
    </div>

    <x-slot name="footer">
        <button class="btn btn-primary" v-on:click="h.d.saveProd()">
            <x-btn-loader showIf="h.d.saving"></x-btn-loader>
            <i class="fas fa-save mx-1"></i>
            <span class="after">
                {{__('product.form.save')}}
            </span>
        </button>
    </x-slot>
</x-modal>