<product-item v-for="(p, pinx) in h.d.data" :product="p"
    :cls="h.d.landList ? 'col-12' : 'col-6 px-1 px-md-2 col-sm-6 col-md-{{$mdWidth ?? 3}}'" :key="p.slug"
    :is_land="h.d.landList" v-on:modal="h.d.openModal($event)"
    v-on:error="h.d.showNotifyNative($event)"
    v-on:exists="h.d.showNotifyNative($event)"
    v-on:added="h.d.pushToCartList($event)"
    v-on:removed="h.d.removeFromCart($event)" :cart-loaded="h.d.cartLoaded"
    v-on:show-cart-loader="h.d.showCartLoader()"
    v-on:hide-cart-loader="h.d.hideCartLoader()"
    v-on:show-wish-loader="h.d.showWishLoaderNative()"
    v-on:hide-wish-loader="h.d.hideWishLoaderNative()">
</product-item>
<product-item-loader v-for="(pol, polinx) in h.d.loadingData"
    :is_land="h.d.landList"
    :cls="h.d.landList ? 'col-12': 'col-6 px-1 px-md-2 col-sm-6 col-md-{{$mdWidth ?? 3}}'" :key="polinx">
</product-item-loader>