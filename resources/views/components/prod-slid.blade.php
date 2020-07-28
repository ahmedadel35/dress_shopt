@props([
'headerCls' => 'bg-primary text-light',
'cls' => 'col-12',
'key' => '',
'loadMore' => '',
'modal' => '',
'title' => ''
])

<div class="{{$cls}} prodcut-slider card border-none p-0">
    <div class="card-header mb-2 {{$headerCls}}">
        <h5>
            <strong>
                {{$title}}
            </strong>
        </h5>
    </div>
    <div class="card-body bg-transparent py-0">
        <product-slider :products="h.d.{{$key}}.data"
            :still="h.d.{{$key}}.remain"
            v-on:load-more="h.d.{{$loadMore}}(h.d.{{$key}}.nextUrl, true)"
            v-on:modal="h.d.{{$modal}}($event)"></product-slider>
    </div>
</div>