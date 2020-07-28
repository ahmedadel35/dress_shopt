import { Component, Prop, Mixins } from "vue-property-decorator";
import Super from "./super";
import CartInterface from "../interfaces/cart";
import ProductMixin from "../mixins/product-mixin";
import ProductShowMixin from "../mixins/product-show-mixin";
import { ProductFeatData, Item } from "./product-show";
import ProductInterface from "../interfaces/product";
import { EMPTY_PRODUCT } from "../mixins/product-mixin";
import Axios, { AxiosResponse } from "axios";
import ProductListMixin from "../mixins/product-list-mixin";

export interface HomeData {
    res: string[];
    cart: CartInterface;
    cartLoader: boolean;
    wishLoader: boolean;
    q: string;
    userId: number;
    feat: ProductFeatData;
    mp: ProductInterface;
    item: Item;
    loadingData: number[];
    remain: number;
    loadingProds: boolean;
    data: ProductInterface[];
    cartLoaded: number;
    scrollTop: number;
    carouselHeight: number;
}

@Component
export default class Home extends Mixins(
    ProductMixin,
    ProductShowMixin,
    ProductListMixin
) {
    public d: HomeData = {
        // all your compnent data will be present in here
        res: [""],
        cart: {
            items: [],
            wish: [],
            count: 0,
            total: 0
        },
        cartLoader: false,
        wishLoader: true,
        userId: 0,
        q: "",
        feat: {
            loading: false,
            data: [],
            nextUrl: "",
            remain: 8
        },
        mp: EMPTY_PRODUCT,
        item: {
            size: 0,
            color: 0,
            qty: 1,
            wishId: -10,
            wishing: true
        },
        loadingData: [],
        remain: 12,
        loadingProds: false,
        data: [],
        cartLoaded: 0.555,
        scrollTop: 0,
        carouselHeight: 300
    };

    public loadFeaturedProds(
        path: string = "/collection/featured",
        append: boolean = false
    ) {
        this.loadFeaturedProdsNative(this.d, path, append);
    }

    public openModal(slug: string) {
        // console.log(slug);
        this.openModalNative(this.d, slug, "homeProductModal", { avg: true });
    }

    public addToCart() {
        if (this.d.cartLoader) {
            return;
        }

        const res = this.addToCartNative(
            this.d.mp,
            this.d.item.qty,
            this.d.item.size,
            this.d.item.color
        );
    }

    public addToWish() {
        this.addToWishList(this.d, "mp").then(res => {
            if (res && res.item) {
                this.pushToCartList({ item: res.item });
            }
        });
    }

    public removeFromCart(id: string) {
        this.removeFromCartNative(id);
    }

    public loadLatestProds() {
        if (this.d.loadingProds) {
            return;
        }
        this.d.loadingProds = true;

        Axios.get("/collection/latest").then(res => {
            if (!res || !res.data) {
                this.error();
                return;
            }
            this.d.loadingData = [];
            this.d.data = [...res.data];
            this.d.loadingProds = false;
        });
    }

    beforeMount() {
        this.attachToGlobal(this, [
            "loadFeaturedProds",
            "openModal",
            "addToCart",
            "addToWish",
            "removeFromCart"
        ]);
        this.fillLoadingData(this.d);
    }

    mounted() {
        this.loadFeaturedProds();
        this.loadLatestProds();

        this.$on("cartDone", _ => {
            this.d.cartLoaded = Math.random() * 10000;
        });

        const carousel = document.querySelector(
            "#homeImagesCarousel"
        ) as HTMLDivElement;
        if (carousel) {
            this.d.carouselHeight = carousel.clientHeight;
        }

        // set carsoul margin top
        const nav = document.querySelector('#index-nav') as HTMLDivElement;
        const bottomNav = document.querySelector('#bottom-nav') as HTMLDivElement;
        const form = document.querySelector('#nav-form') as HTMLDivElement;

        carousel.style.marginTop = `-${nav.offsetHeight + form.offsetHeight + bottomNav.offsetHeight}px`;
    }
}
