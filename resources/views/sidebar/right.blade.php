<sidebar side="right" ref="cartSidebar" class="bg-light text-dark">
    <h4 class="px-2 my-4">
        <span class="spinner-grow bg-dark" :class="{'d-none': !h.d.cartLoader}"
            role="status" aria-hidden="true"></span>
        <i class="fa fas fa-cart-plus"></i>
        {{__('t.scart.shopp')}}
        <sup class="badge badge-danger align-top">
            @{{h.d.formatNum(h.d.cart.count)}}
        </sup>
    </h4>
    <ul class="list-unstyled px-2">
        <x-cart-list data="items" type="cart"></x-cart-list>
        <li class="mt-5">
            <h5 class="font-weight-bolder">
                <span class="text-left">
                    @lang('t.index.overTotal'):
                </span>

                <span dir='ltr' class="text-right float-right text-primary"
                    style="font-size: large;">LE
                    @{{h.d.formatNum(h.d.cart.total)}}</span>
            </h5>
            <div class="form-group text-center">
                <x-link-loader href="cart/view"
                    class="btn-primary col-5 mr-2">
                    @lang('t.index.viewC')
                </x-link-loader>
                <x-link-loader href="cart/checkout"
                    class="btn-success col-5 mr-2">
                    @lang('t.index.checkout')
                </x-link-loader>
            </div>
        </li>
    </ul>
</sidebar>

<sidebar side="right" ref="wishSidebar" class="bg-light text-dark">
    <h4 class="px-2 my-4">
        <i :class="{'animate__heartBeat animate__infinite': h.d.wishLoader}"
            class="fa fas fa-heart animate__animated"></i>
        {{__('t.scart.wishlist')}}
        <sup class="badge badge-danger align-top">
            @{{h.d.formatNum(h.d.cart.wish.length)}}
        </sup>
    </h4>
    <ul class="list-unstyled px-2">
        <x-cart-list data="wish" type="wish"></x-cart-list>
    </ul>
</sidebar>