<div class="col-12 col-sm-6 text-left">
    <h4 id="ptitle" class="text-capitalize">
        @isset ($title)
        {{$title === 'daily' ? __('t.show.daily') : $title}}
        @else
        {{__('t.all_p')}}
        @endisset
    </h4>
</div>

<div class="col-12 text-right">
    @lang('t.view')
    <div class="d-inline">
        <button class="btn btn-outline-primary mr-1"
            v-on:click.prevent="h.d.landList = true"
            :class="h.d.landList ? 'active' : ''" data-toggle="tooltip"
            data-placement="top" title="{{__('product.viewAslist')}}">
            <i class="fa fa-bars"></i>
        </button>
        <button class="btn btn-outline-primary ml-1"
            v-on:click.prevent="h.d.landList = false"
            :class="h.d.landList ? '' : 'active'" data-toggle="tooltip"
            data-placement="top" title="{{__('product.notViewAslist')}}">
            <i class="fa fa-th-large"></i>
        </button>
    </div>
    <span>
        @lang('t.sortBy'): <div class="btn-group dropleft">
            <div class="dropdown d-inline">
                <button
                    class="btn btn-outline-info btn-clear dropdown-toggle text-capitalize"
                    type="button" id="dropdownMenuFilterList"
                    data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <span>
                        <i class="fas fa-sort mx-1"></i>
                        <template v-if="!h.d.currentFilter.length">
                            {{__('t.show.pop')}}
                        </template>
                        <template v-else>
                            @{{h.d.currentFilter}}
                        </template>
                    </span>
                </button>
                <div class="dropdown-menu text-capitalize"
                    aria-labelledby="dropdownMenuFilterList">
                    @foreach ([
                    'pop' => __('t.show.pop'),
                    'rated' => __('t.show.rated'),
                    'lowTo' => __('t.show.lowTo'),
                    'highTo' => __('t.show.highTo')
                    ] as $k => $v)
                    <a class="dropdown-item" href="#"
                        v-on:click.prevent="h.d.filterData('{{$k}}', '{{$v}}')"
                        :class="{active: h.d.currentFilter === '{{$k}}'}">
                        {{$v}}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </span>
</div>