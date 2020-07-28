<div class=" mb-3">
    <div class="">
        <button class="btn btn-danger d-none d-md-block"
            v-on:click="h.d.resetAllfilters()">
            {{-- TODO spinner loader --}}
            <span class="after">
                {{__('t.removeAllfillters')}}
            </span>
        </button>
        <a href="#" type="button" v-if="h.d.scrollTop > 200"
            class="filterBtn rounded-circle p-2 btn btn-primary position-fixed d-md-none"
            :class="{'bg-success border-success': h.d.hasFilterActive()}"
            v-on:click.prevent.stop="h.d.openSide('filterSidebar')">
            <i class="fas fa-filter mx-1"></i>
            {{-- <span class="after">
                {{__('product.filters.openFilters')}}
            </span> --}}
        </a>
    </div>
</div>
<div class="d-none d-md-block">
    @include('products.index.all-filters')
</div>