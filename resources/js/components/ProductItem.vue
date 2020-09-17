<template>
    <div :class="cls">
        <div
            class="card product-card p-0 position-relative"
            :class="[is_land ? 'mb-3' : 'mb-1', dir]"
            :id="'card' + p.id"
            @mouseover="onHover($event)"
            @mouseout="toggleHover($event)"
        >
            <div class="row" :class="{ 'no-gutters': is_land }">
                <div
                    class="overflow-hidden"
                    :class="is_land ? 'col-4' : 'col-12'"
                >
                    <a
                        :href="`/${lang}/product/${p.slug}`"
                        v-on:click.prevent.stop=""
                    >
                        <img
                            v-lazy="p.images[0]"
                            class="w-100"
                            :class="{
                                'card-img': !isTouch || !is_land,
                                'card-img-top': isTouch,
                                'p-1 border': is_land
                            }"
                            :alt="p.title"
                        />
                    </a>
                    <span
                        class="badge badge-danger p-2 off-badge position-absolute"
                    >
                        <strong> {{ offTxt }} {{ (p.save).toFixed(2) }} % </strong>
                    </span>
                    <a
                        href="#"
                        ref="love"
                        class="love position-absolute"
                        :class="{
                            'd-none': !isTouch && !is_land,
                            'animate__animated animate__heartBeat animate__infinite disabled': addingToWish,
                            active: in_wish
                        }"
                        @click.prevent="addToWish()"
                    >
                        <i
                            class="fa fa-heart"
                            :class="{
                                'text-light': !in_wish,
                                'text-primary': in_wish
                            }"
                        ></i>
                    </a>
                    <div class="text-center p-1 border border-danger text-danger font-weight-bold" v-if="p.qty < 1">
                        {{outSockTxt}}
                    </div>
                </div>
                <div
                    ref="overlay"
                    v-if="!isTouch || !is_land"
                    class="d-none card-img-overlay text-white mx-auto"
                >
                    <div class="content pt-3 position-relative my-0">
                        <h5 ref="title" class="card-title">
                            <a
                                :href="`/${lang}/product/${p.slug}`"
                                class="text-light"
                                >{{ p.title }}</a
                            >
                        </h5>
                        <star-rate
                            ref="starRate"
                            :percent="p.rate_avg"
                            :count="p.rates ? p.rates.length : 0"
                            :countClass="isTouch ? 'muted' : 'light'"
                            :product-slug="p.slug"
                        ></star-rate>
                        <div
                            ref="btns"
                            class="bottom-btns position-absolute text-center"
                        >
                            <button
                                class="btn btn-warning mb-2"
                                @click="addToCart()"
                                :class="{
                                    disabled:
                                        addingToCart || in_cart || p.qty < 1
                                }"
                            >
                                <span
                                    v-show="addingToCart"
                                    class="spinner-border spinner-border-sm align-middle"
                                    role="status"
                                    aria-hidden="true"
                                ></span>
                                <i
                                    v-show="!addingToCart"
                                    class="fa fas fa-cart-plus"
                                ></i>
                            </button>
                            <button
                                class="quickbtn btn btn-primary mb-2"
                                @click.prevent="$emit('modal', p.slug)"
                            >
                                <i class="fa fas fa-search-plus"></i>
                                {{ btnTxt[1] }}
                            </button>
                        </div>
                    </div>
                </div>
                <div
                    class="card-body text-primary"
                    :class="is_land ? 'col-8 pl-2' : 'col-12 ml-2'"
                >
                    <h5
                        ref="title"
                        v-if="isTouch || is_land"
                        class="card-title"
                    >
                        <a :href="`/${lang}/product/${p.slug}`">{{
                            p.title
                        }}</a>
                    </h5>
                    <strong class="d-block">
                        <p>
                            LE {{ formatter.format(p.saved_price) }}<br />
                            <span class="text-muted">
                                <del>LE {{ formatter.format(p.price) }}</del>
                                <span class="ml-3" v-if="youSave && is_land">
                                    {{ btnTxt[2] }} {{ youSave }}
                                </span>
                            </span>
                        </p>
                    </strong>
                    <star-rate
                        v-if="isTouch || is_land"
                        :percent="p.rate_avg"
                        :count="p.rates ? p.rates.length : 0"
                        :product-slug="p.slug"
                    ></star-rate>
                    <span class="text-secondary" v-if="is_land">
                        {{ p.info }}
                    </span>
                </div>
                <div
                    v-if="isTouch || is_land"
                    class="card-footer text-center col-12"
                    :class="is_land ? 'border-none bg-white' : ''"
                >
                    <button
                        class="btn btn-outline-primary transition"
                        @click="addToCart()"
                        :disabled="addingToCart || in_cart || p.qty < 1"
                    >
                        <span
                            v-show="addingToCart"
                            class="spinner-border spinner-border-sm mr-1 align-middle"
                            role="status"
                            aria-hidden="true"
                        ></span>
                        <span class="sr-only">Loading...</span>
                        <i class="fa fas fa-cart-plus"></i>
                        {{ btnTxt[0] }}
                    </button>
                    <button
                        class="btn btn-info transition d-none d-sm-inline text-light"
                        @click.prevent="$emit('modal', p.slug)"
                    >
                        <i class="fa fas fa-search-plus text-light"></i>
                        {{ btnTxt[1] }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<style lang="scss"></style>
<script lang="ts">
import { Vue, Component, Prop, Watch } from "vue-property-decorator";
import ProductInterface from "../interfaces/product";
import StarRate from "./StarRate.vue";
import Axios from "axios";
import ProductList, { ProductListData } from "../pages/product-list";
import CartItemInterface from "../interfaces/cart-item";

@Component
export default class ProductItem extends Vue {
    @Prop({ required: true }) public product: ProductInterface;
    @Prop({ type: Boolean }) public is_land: boolean;
    @Prop({ type: String }) public catSlug: string;
    @Prop({ type: Boolean, default: false }) public isAdmin: boolean;
    @Prop({ type: Boolean, default: false }) public isSuper: boolean;
    @Prop({ type: Number, default: 0 }) public userId: number;
    @Prop({ type: Number, default: 5 }) public cartLoaded: number;
    @Prop({ type: String }) public cls: string;

    public p: ProductInterface = this.product;
    public showLoader: string = "d-none";
    public isTouch: boolean = this.isTouchScreen();
    public hiddenOverLay: boolean = true;
    public dir: string = "";
    public formatter = new Intl.NumberFormat("en-EG");
    public btnTxt: string[] = ["add to cart", "quick view", "you save"];
    public addingToCart: boolean = false;
    public in_cart: boolean = false;
    public addingToWish: boolean = false;
    public in_wish: boolean = false;
    public itemId: { wish: number; cart: number } = {
        wish: 0,
        cart: 0
    };

    public onHover(ev) {
        if (this.isTouch || this.is_land) return;
        this.startAnimation();
        (this.$refs.overlay as HTMLElement).classList.remove("d-none");
    }

    public toggleHover(ev) {
        if (this.isTouch || this.is_land) return;
        (this.$refs.overlay as HTMLElement).classList.add("d-none");
        (this.$refs.love as HTMLElement).classList.add("d-none");
    }

    public startAnimation() {
        const title = this.$refs.title as HTMLElement;
        if (title && !title.classList.contains("animate__animated")) {
            title.classList.add("animate__animated", "animate__fadeInDown");
        }

        const love = (this.$refs.love as HTMLElement).classList;
        love.remove("d-none");
        if (!love.contains("animate__animated")) {
            love.add(
                "animate__animated",
                "animate__rubberBand",
                "animate__repeat-3"
            );
        }

        const btns = (this.$refs.btns as HTMLElement).classList;
        if (!btns.contains("animate__animated")) {
            btns.add("animate__animated", "animate__fadeInUp");
        }

        (this.$refs.starRate as StarRate).cls =
            "animate__animated animate__fadeInRight";
    }

    public setSameHeight() {
        // const overlay = this.$refs.overlay as HTMLDivElement;
        // // console.log((document.querySelector(".card-img") as HTMLImageElement).height);
        // if (!overlay) return;
        // overlay.style.height = `${
        //     (document.querySelector(".card-img") as HTMLImageElement).height
        // }px`;
    }

    public isTouchScreen() {
        const prefixes = " -webkit- -moz- -o- -ms- ".split(" ");

        const mq = query => {
            return window.matchMedia(query).matches;
        };

        if (
            "ontouchstart" in window ||
            // @ts-ignore
            (window.DocumentTouch && document instanceof DocumentTouch)
        ) {
            return true;
        }
        const query = [
            "(",
            prefixes.join("touch-enabled),("),
            "heartz",
            ")"
        ].join("");
        return mq(query);
    }

    get youSave(): string {
        return this.formatter.format(
            (this.p.price as number) - (this.p.saved_price as number)
        );
    }

    get lang(): string {
        return document.documentElement.lang;
    }

    get outSockTxt(): string {
        return window['xjs']['xlang'][14];
    }

    // get price(): string {
    //     return this.formatter.format(this.p.price as number);
    // }

    // get saved_price(): string {
    //     return this.formatter.format(this.p.saved_price as number);
    // }

    public addToCart() {
        // console.log(this.$parent);
        if (this.in_cart || this.addingToCart || this.p.qty < 1) {
            return;
        }
        this.addingToCart = true;
        // (this.$parent as ProductList).showCartLoader();
        this.$emit("show-cart-loader");

        // console.log(this.p);

        Axios.post(`/cart/${this.p.slug}`, {
            qty: 1,
            color: 0,
            size: 0
        })
            .then(res => {
                if (!res || !res.data || res.data.errors) {
                    this.$emit("error", 1);
                    this.addingToCart = false;
                    // (this.$parent as ProductList)?.hideCartLoader();
                    this.$emit("hide-cart-loader");
                    return;
                }

                if (res.data.exists) {
                    this.$emit("exists", 0);
                    // (this.$parent as ProductList)?.hideCartLoader();
                    this.$emit("hide-cart-loader");
                    this.addingToCart = false;
                    this.in_cart = true;
                    return;
                }

                this.$emit("added", res.data);
                this.addingToCart = false;
                this.in_cart = true;
                // (this.$parent as ProductList).hideCartLoader();
                this.$emit("hide-cart-loader");
            })
            .catch(err => {
                this.$emit("error", 1);
                this.addingToCart = false;
                // (this.$parent as ProductList)?.hideCartLoader();
                this.$emit("hide-cart-loader");
            });
    }

    public addToWish() {
        if (this.addingToWish) {
            return;
        }

        // (this.$parent as ProductList)?.showWishLoaderNative();
        this.$emit("show-wish-loader");
        this.addingToWish = true;
        // console.log(this.in_wish);
        if (this.in_wish) {
            Axios.post(`/cart/${this.itemId.wish}/delete`, {
                wish: true
            })
                .then(res => {
                    if (!res || res.status !== 204) {
                        this.addingToWish = false;
                        this.$emit("error", 1);
                        // (this.$parent as ProductList).hideWishLoaderNative();
                        this.$emit("hide-wish-loader");
                        return;
                    }

                    this.addingToWish = false;
                    this.in_wish = false;
                    this.$emit("removed", this.itemId.wish);
                    // (this.$parent as ProductList).hideWishLoaderNative();
                    this.$emit("hide-wish-loader");
                })
                .catch(err => this.$emit("error", 1));
            // (this.$parent as ProductList)?.hideWishLoaderNative();
            this.$emit("hide-wish-loader");
            return;
        }

        Axios.post("/cart/" + this.p.slug, {
            qty: 1,
            size: 0,
            color: 0,
            wish: true
        }).then(res => {
            if (!res || !res.data || res.data.errors) {
                this.$emit("error", 1);
                this.addingToWish = false;
                // (this.$parent as ProductList)?.hideWishLoaderNative();
                this.$emit("hide-wish-loader");
                return;
            }

            if (res.data.exists) {
                this.$emit("exists", 0);
                this.addingToWish = false;
                this.in_wish = true;
                // (this.$parent as ProductList).hideWishLoaderNative();
                this.$emit("hide-wish-loader");
                return;
            }

            this.addingToWish = false;
            this.in_wish = true;
            this.itemId.wish = res.data.item.id;
            this.$emit("added", res.data);
            // (this.$parent as ProductList).hideWishLoaderNative();
            this.$emit("hide-wish-loader");
        });
    }

    public checkInCart() {
        const cartIndx = ((this.$parent as ProductList).d.cart
            .items as CartItemInterface[]).findIndex(
            i => this.p.id === i.product_id
        );
        if (cartIndx > -1) {
            this.in_cart = true;
            this.itemId.cart = ((this.$parent as ProductList).d.cart
                .items as CartItemInterface[])[cartIndx].id;
        } else {
            this.in_cart = false;
            this.itemId.cart = 0;
        }

        const wishIndx = ((this.$parent as ProductList).d.cart
            .wish as CartItemInterface[]).findIndex(
            i => this.p.id === i.product_id
        );
        if (wishIndx > -1) {
            this.in_wish = true;
            this.itemId.wish = ((this.$parent as ProductList).d.cart
                .wish as CartItemInterface[])[wishIndx].id;
            return;
        }
        this.in_wish = false;
        this.itemId.wish = 0;
        // console.log(this.itemId.wish, this.itemId.cart);
    }

    get offTxt(): string {
        return window["xjs"].xlang[11];
    }

    @Watch("cartLoaded")
    onCartLoadedChange(val: number) {
        // console.log("changed");
        this.checkInCart();
    }

    mounted() {
        if (!this.isTouch || !this.is_land) {
            this.setSameHeight();
        }
        this.dir = document.dir;

        if (this.cartLoaded !== 5) {
            // console.log("changed");
            this.checkInCart();
        }

        // load custom translations
        const xlang = window["xjs"].xlang;
        this.btnTxt = [xlang[9], xlang[8], xlang[10]];

        // check if product exists in cart
        // setTimeout(_ => , 750);
    }
}
</script>
