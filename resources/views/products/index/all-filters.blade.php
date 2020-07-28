<filter-collabse title="{{__('product.filters.size')}}"
    header-cls="bg-primary text-light">
    <text-select id="" :array="h.d.sizes" :multi="true" emit-id="size"
        v-on:size-done="h.d.setSideFilters('size', $event)"
        :reset="h.d.resetFilters">
    </text-select>
</filter-collabse>

<filter-collabse title="{{__('t.show.color')}}"
    header-cls="bg-primary text-light">
    <text-select id="" :array="h.d.colors" :multi="true" :is-color="true"
        emit-id="color" v-on:color-done="h.d.setSideFilters('color', $event)"
        :reset="h.d.resetFilters"></text-select>
</filter-collabse>

<filter-collabse title="{{__('t.price')}}" header-cls="bg-primary text-light">
    <div>
        <input type="range" class="custom-range d-inline-block col-6 p-0 m-0"
            :min="h.d.prices.min" :max="h.d.selectedPrices.max" id="price-range"
            v-model="h.d.selectedPrices.min">
        <input type="range"
            class="custom-range d-inline-block col-6 p-0 m-0 ml-n1"
            :min="(h.d.prices.min+h.d.prices.max)/2" :max="h.d.prices.max"
            id="price-econdrange" v-model="h.d.selectedPrices.max">
    </div>
    <div class="d-block">
        <strong class="text-center text-primary">
            LE @{{h.d.selectedPrices.min}} - LE @{{h.d.selectedPrices.max}}
        </strong>
    </div>
    <div class="d-block mt-2 text-right">
        <button class="btn btn-primary"
            v-on:click.prevent="h.d.setSideFilters('price')">
            <span class="after">
                {{__('t.search')}}
            </span>
        </button>
    </div>
</filter-collabse>

<filter-collabse title="{{__('t.rating')}}" header-cls="bg-primary text-light">
    <span class="rateContainer transition"
        v-on:click="h.d.setSideFilters('rate', 5)">
        <star-rate :percent="5"></star-rate>
    </span>
    <span class="rateContainer transition"
        v-on:click="h.d.setSideFilters('rates', 4)">
        <star-rate :percent="4"></star-rate>
    </span>
    <span class="rateContainer transition"
        v-on:click="h.d.setSideFilters('rates', 3)">
        <star-rate :percent="3"></star-rate>
    </span>
    <span class="rateContainer transition"
        v-on:click="h.d.setSideFilters('rates', 2)">
        <star-rate :percent="2"></star-rate>
    </span>
    <span class="rateContainer transition"
        v-on:click="h.d.setSideFilters('rates', 1)">
        <star-rate :percent="1"></star-rate>
    </span>
</filter-collabse>

<button class="btn btn-danger mt-4 text-center"
    v-on:click="h.d.resetAllfilters()">
    {{-- TODO spinner loader --}}
    <span class="after">
        {{__('t.removeAllfillters')}}
    </span>
</button>