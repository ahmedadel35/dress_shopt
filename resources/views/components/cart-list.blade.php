@props([
'data' => 'items',
'type' => 'cart',
'hasRemove' => true,
'shopList' => false,
'nocart' => false
])
<li class="media border-bottom py-2 overflow-hidden" @if ($nocart)
    v-for="c in h.d.{{$data}}" @else v-for="c in h.d.cart.{{$data}}" @endif
    :key="'{{random_int(2, 6555654)}}' + c.id">
    <img :src="c.product.images[0]" style="width: 6rem;"
        class="cartImage align-self-center mr-3 img-thumbnail bg-light tranistion"
        :alt="c.product.title">
    <div class="media-body pr-1">
        <div class="row">
            <strong class="m-0 pr-0 col-10">
                <a :href="'/{{app()->getLocale()}}/product/' + c.product.slug">
                    @{{c.product.title}}
                </a>
            </strong>
            @if ($hasRemove)
            <span class="m-0 py-0 col-2 text-right">
                <button type="button" class="close pl-2 pr-1 py-1 text-danger"
                    aria-label="Close"
                    v-on:click.prevent.stop="h.d.removeItem('{{$type}}', c)">
                    {{-- <span aria-hidden="true">&times;</span> --}}
                    <span class="fas fa-trash-alt"></span>
                </button>
            </span>
            @endif
        </div>

        <div class="row">
            @if ($nocart)
            <p class="col-12 text-left text-primary">
                LE @{{h.d.formatNum(c.product.saved_price || 0)}}
            </p>
            @endif
            <p class="col-12">
                @if ($type === 'wish')
                <span class="float-left">
                    LE @{{h.d.formatNum(c.product.saved_price || 0)}}
                </span>
                @else
                <span class="float-left">
                    @lang('t.index.QTY'):
                    @{{c.qty}}
                </span>
                <span class="float-right text-danger">
                    @{{c.product.qty}}
                    <span v-if="c.product && c.product.qty > 0">
                        @lang('t.index.stock')
                    </span>
                    <span v-else>
                        @lang('product.outStock')
                    </span>
                </span>
                @endif
            </p>
        </div>

        {{$slot}}
    </div>
</li>
@unless($nocart)
<li v-for="cod in h.d.cart.loaders" :key="cod * Math.random()"
    class="border-bottom py-2 overflow-hidden">
    <content-loader width="{{$shopList ? 450 : 350}}"
        height="{{$shopList ? 80 : 60}}" primary-color="#cbcbcd"
        secondary-color="#02103b">
        <rect x="3" y="0" rx="5" ry="5" width="66" height="57"></rect>
        <rect x="83" y="5" rx="0" ry="0" width="208" height="11"></rect>
        <rect x="85" y="23" rx="0" ry="0" width="45" height="9"></rect>
        <rect x="280" y="22" rx="0" ry="0" width="45" height="9"></rect>
        @if ($shopList)
        <rect x="86" y="39" rx="0" ry="0" width="45" height="9"></rect>
        <rect x="253" y="39" rx="0" ry="0" width="45" height="9"></rect>
        <rect x="86" y="55" rx="0" ry="0" width="45" height="9"></rect>
        <rect x="253" y="55" rx="0" ry="0" width="45" height="9"></rect>
        <rect x="88" y="70" rx="0" ry="0" width="62" height="12"></rect>
        <rect x="160" y="70" rx="0" ry="0" width="62" height="12"></rect>
        @endif
    </content-loader>
</li>
@endunless