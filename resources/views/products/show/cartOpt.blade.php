<template>
    <div class="pb-2 mb-3">
        <text-select id="size" title="{{__('product.size')}}"
            :array="h.d.product.sizes" v-on:size-done="h.d.pItem.size = $event"
            :start="parseInt(h.d.pItem.size)"
            :disabled="h.d.product.cartQty === 5 || h.d.product.qty < 1 || h.d.cartLoader">
        </text-select>
    </div>
    <div class="pb-1 mb-3">
        <text-select id="color" title="{{__('product.color')}}"
            :array="h.d.product.colors" :is-color="true"
            v-on:color-done="h.d.pItem.color = $event"
            :start="parseInt(h.d.pItem.color)"
            :disabled="h.d.product.cartQty === 5 || h.d.product.qty < 1 || h.d.cartLoader">
        </text-select>
    </div>

    <div class="row">
        <strong class="text-capitalize col-3 pr-0">
            {{__('product.amount')}}
        </strong>
        <div class="col-9 px-0">
            <number-select :count="h.d.product.qty"
                v-on:current-amount="h.d.pItem.qty = $event"
                :start="parseInt(h.d.pItem.qty)"
                :disabled="h.d.product.cartQty === 5 || h.d.product.qty < 1 || h.d.pItem.wishId < -10 || h.d.cartLoader">
            </number-select>
        </div>
        <div class="col-12">
            <div class="row">
                <div class="col-12 col-sm-6 mb-2">
                    <strong>
                        {{__('product.Availbale')}}:
                        @{{h.d.product.qty}}
                        <span v-if="h.d.product.qty < 20" class="text-danger">
                            {{__('product.only_left')}}
                        </span>
                    </strong>
                </div>
                <div class="col-12 col-sm-6 mb-2" v-if="h.d.pItem.qty > 1">
                    <strong class="text-primary">
                        {{__('product.Total')}}: LE <span
                            v-text="h.d.formatNum(h.d.pItem.qty * h.d.product.saved_price)"></span>
                    </strong>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <button class="btn btn-primary btn-block"
                v-on:click="h.d.addToCart()" :disabled="h.d.cartLoader">
                <span v-show="h.d.addingToCart"
                    class="spinner-border spinner-border-sm mr-1 align-middle" role="status"
                    aria-hidden="true"></span>
                <i class="fas fa-cart-plus mx-1"></i>
                <span class="after">
                    {{__('product.AddToCart')}}
                </span>
            </button>
        </div>
    </div>
</template>