<form
    class="searchForm form-inline @isset($sm) {{$sm}} @else my-2 my-lg-0 @endisset needs-validation "
    method="get" action="/{{app()->getLocale()}}/products/find"
     @url('*/products/*')v-on:submit.prevent.stop="h.d.searchFor()" @endurl>
    <div class="input-group">
        <div class="input-group-prepend">
            <button type="submit"
                class="input-group-text btn-clear bg-transparent text-light"
                id="search-query{{random_int(3, 324234234)}}" aria-label="search">
                <i class="fas fa-search"></i>
            </button>
        </div>
        <input type="search" class="form-control"
            :class="{'is-invalid': h.d.q.length && h.d.q.length < 3}"
            id="search-query{{random_int(3, 324234234)}}" placeholder="{{__('t.show.serpl')}}"
            aria-labelledby="search" minlength="3" name="q" v-model.trim="h.d.q" />
    </div>
</form>