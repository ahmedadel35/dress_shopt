@props(['id' => 'productModal', 'item' => 'item', 'withoutitem' => false])

<div class="modal fade" id="{{$id}}" tabindex="-1" role="dialog"
    aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-light">
                <x-linkload class="modal-title w-50 overflow-hidden"
                    title="h.d.mp.title" height="8" radius="0">
                </x-linkload>
                <button type="button" class="close" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <img-slider ref="slider" :save="h.d.mp.save"
                            :images="h.d.mp.images" :options="h.d.sliderOpt">
                        </img-slider>
                    </div>
                    <div class="col-12 col-md-6">
                        <x-linkload class="modal-title w-100 overflow-hidden"
                            title="h.d.mp.title" slug="h.d.mp.slug"
                            :loader="true">
                        </x-linkload>
                        <div :class="{'d-none': !h.d.mp.title}">
                            <strong class="text-primary d-block">
                                <hr />
                                <div class="row">
                                    <div class="col-12 col-sm-6 mb-2">
                                        <span>
                                            LE @{{h.d.mp.saved_price || ''}}
                                        </span>
                                        <template v-if="h.d.mp.save">
                                            <del class="mx-2 text-muted mb-2">
                                                LE @{{h.d.mp.price || ''}}
                                            </del>
                                            <span class="ml-2 text-muted d-inline-block">
                                                {{__('t.youSave')}} LE
                                                @{{h.d.formatNum(h.d.mp.priceInt - h.d.mp.saved_priceInt)}}
                                            </span>
                                        </template>

                                    </div>
                                    <div
                                        class="col-12 col-sm-6 text-right mb-2">
                                        <star-rate :percent="h.d.mp.rate_avg"
                                            :count="h.d.mp.rates.length"
                                            :product-slug="h.d.mp.slug">
                                        </star-rate>
                                    </div>
                                </div>
                            </strong>

                            <p class="text-secndary">
                                @{{h.d.mp.info}}
                                <hr />
                            </p>

                            @unless($withoutitem)
                            <div class="pb-2 mb-3">
                                <text-select id="size" :array="h.d.mp.sizes"
                                    v-on:size-done="h.d.item.size = $event"
                                    :start="parseInt(h.d.item.size)"
                                    :disabled="h.d.mp.cartQty === 5">
                                </text-select>
                            </div>
                            <div class="pb-1 mb-3">
                                <text-select id="color" :array="h.d.mp.colors"
                                    :is-color="true"
                                    v-on:color-done="h.d.item.color = $event"
                                    :start="parseInt(h.d.item.color)"
                                    :disabled="h.d.mp.cartQty === 5">
                                </text-select>
                            </div>

                            <div class="">
                                <strong class="text-capitalize">
                                    {{__('product.Amount')}}
                                </strong>
                                <number-select :count="h.d.mp.qty"
                                    v-on:current-amount="h.d.item.qty = $event"
                                    :start="parseInt(h.d.item.qty)"
                                    :disabled="h.d.mp.cartQty === 5">
                                </number-select>
                            </div>

                            <div class="row">
                                <div class="col-12 col-sm-6 mb-3">
                                    <strong>
                                        {{__('product.Availbale')}}:
                                        @{{h.d.mp.qty}}
                                        <span v-if="h.d.mp.qty < 20"
                                            class="text-danger">
                                            {{__('product.only_left')}}
                                        </span>
                                    </strong>
                                </div>
                                <div class="col-12 col-sm-6"
                                    v-if="h.d.item.qty > 1">
                                    <strong class="text-primary">
                                        {{__('product.Total')}}: LE <span
                                            v-text="h.d.formatNum(h.d.item.qty * h.d.mp.saved_priceInt)"></span>
                                    </strong>
                                </div>
                            </div>
                            @endunless
                        </div>

                        <div :class="{'d-none': h.d.mp.title}">
                            <hr />
                            <quickview-loader></quickview-loader>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                @unless($withoutitem)
                <div class="row w-100">
                    <div class="col-6">
                        <x-social class="btn-clear hoverPrimary">
                            <li class="nav-item">
                                <a class="nav-link btn btn-clear"
                                    :class="{active: h.d.item.wishId > -1,
                                    'animate__animated animate__heartBeat animate__infinite': h.d.item.wishing}"
                                    v-on:click.prevent="h.d.addToWish('item', 'mp')"
                                    href="#">
                                    <i class="fas fa-heart"></i>
                                </a>
                            </li>
                        </x-social>
                    </div>
                    <div class="col-6 text-center">
                        <button type="button" class="btn btn-primary"
                            v-on:click="h.d.addToCart('item', 'mp')">
                            <span :id="'spinnerLoader' + h.d.mp.id"
                                class="d-none spinner-border spinner-border-sm mr-1 align-middle"
                                role="status" aria-hidden="true"></span>
                            <span class="after">
                                {{__('product.AddToCart')}}
                            </span>
                        </button>
                    </div>
                </div>
                @endunless
            </div>
        </div>
    </div>
</div>