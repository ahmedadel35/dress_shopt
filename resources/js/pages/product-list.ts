import { Component, Mixins } from "vue-property-decorator";
import Super from "./super";
import ProductInterface from "../interfaces/product";
import Axios from "axios";
import SliderInterface from "../interfaces/slider";
import ImgSlider from "../components/ImgSlider.vue";
import HistoryDocInterface from "../interfaces/history-doc";
import CartInterface from "../interfaces/cart";
import CartItemInterface from "../interfaces/cart-item";
import ProductItem from "../components/ProductItem.vue";
import ProductMixin, { EMPTY_PRODUCT } from "../mixins/product-mixin";
import ProductListMixin from "../mixins/product-list-mixin";

export interface Param {
    id: string;
    txt: string;
    checked: boolean;
}

export interface Price {
    min: number;
    max: number;
}

export interface ProductListData {
    data: ProductInterface[];
    mp: ProductInterface;
    loadingData: number[];
    landList: boolean;
    sliderOpt: SliderInterface;
    cat_slug: string;
    nextUrl: string;
    remain: number;
    loadingProducts: boolean;
    scroll: number;
    activeSlug: string;
    empty: boolean;
    doc: HistoryDocInterface;
    cart: CartInterface;
    item: {
        size: number;
        color: number;
        qty: number;
        wishId: number;
        wishing: boolean;
    };
    cartLoaded: number;
    cartLoader: boolean;
    wishLoader: boolean;
    currentFilter: string;
    currentFilterIndx: string;
    colors: Param[];
    sizes: Param[];
    prices: Price;
    selectedPrices: Price;
    filters: {
        sizes: string[];
        colors: string[];
        price: Price;
        stars: number;
    };
    q: string;
    searching: boolean;
    resetFilters: number;
    userId: number;
    scrollTop: number;
}

@Component
export default class ProductList extends Mixins(
    ProductMixin,
    ProductListMixin
) {
    public d: ProductListData = {
        data: [],
        mp: EMPTY_PRODUCT,
        loadingData: [],
        landList: false,
        sliderOpt: {},
        cat_slug: this.extractSlug(),
        nextUrl: "",
        remain: 15,
        loadingProducts: true,
        scroll: 0,
        activeSlug: this.extractSlug(),
        empty: false,
        doc: {
            route: "",
            method: "GET",
            info: "",
            url_with_params: "",
            test_curl: "",
            response: "",
            res_doc: [200, ""],
            headers: [],
            url_params: [],
            query: [],
            parent: ""
        },
        cart: {
            items: [],
            wish: [],
            count: 0,
            total: 0
        },
        item: {
            size: 0,
            color: 0,
            qty: 1,
            wishId: 0,
            wishing: false
        },
        cartLoaded: 0.555,
        cartLoader: true,
        wishLoader: true,
        currentFilter: "",
        currentFilterIndx: "pop",
        colors: [],
        sizes: [],
        prices: {
            min: 0,
            max: 0
        },
        selectedPrices: { min: 0, max: 0 },
        filters: {
            sizes: [],
            colors: [],
            price: {
                min: 0,
                max: 0
            },
            stars: 0
        },
        q: "",
        searching: false,
        resetFilters: Math.random(),
        userId: 0,
        scrollTop: 0
    };

    public openModal(slug: string) {
        this.openModalNative(this.d, slug, "productModal");
    }

    public loadData(
        path: string = `/collection/${this.d.cat_slug}`,
        append: boolean = false,
        isBack: boolean = false,
        sort: string | "pop" | "rated" | "lowTo" | "highTo" = this.d
            .currentFilterIndx
    ) {
        // console.log(this.d.q);
        if (!append && !this.d.searching) {
            this.d.activeSlug = path.split("/")[2];
        }

        if (this.d.searching) {
            this.d.activeSlug = this.d.q;
        }

        this.d.empty = false;

        this.showLoader(append);

        Axios.get(path, {
            params: { sort, filters: this.d.filters, q: this.d.q }
        }).then((res: any) => {
            if (!res || !res.data.data) {
                this.error();
                return;
            }

            this.setDoc(this.d.activeSlug, isBack, append, this.d.searching);

            if (!res.data.data.length) {
                this.hideLoader();
                this.d.empty = true;
                return;
            }

            res = res.data;

            this.setDoc(this.d.activeSlug, true, append, this.d.searching);

            if (append) {
                this.d.data = this.d.data.concat([...res.data]);
                // setTimeout(_ => this.hideLoader(append), 5000);
            } else {
                if (this.d.scrollTop > 10) {
                    // window.scrollTo(0, 0);
                    // console.log("scrolling");
                    document.body.scrollTop = 0;
                    document.documentElement.scrollTop = 0;
                }
                this.d.data = [...res.data];
                // this.hideLoader(append);
            }

            this.doCalc(res.data, append);

            this.d.nextUrl = res.next_page_url || "";
            this.d.remain = res.total - res.to;
            this.d.loadingProducts = false;
            this.d.searching = false;

            // setTimeout(_ => this.hideLoader(append), 4000);
            this.hideLoader(append);
        });
    }

    public doCalc(data: ProductInterface[], append: boolean) {
        let prices: number[] = [this.d.prices.min, this.d.prices.max];
        if (!append) {
            // this.d.colors = [];
            // prices = [];
        }

        data.map(p => {
            this.addOrRemove("colors", p);
            this.addOrRemove("sizes", p);
            prices.push(p.saved_price as number);
        });

        prices = prices.sort((a, b) => a - b);
        // console.log(prices);
        this.d.prices = {
            min: prices[0],
            max: prices[prices.length - 1]
        };
        this.d.selectedPrices.max = Math.round(
            (this.d.prices.min + this.d.prices.max) / 2 + this.d.prices.min
        );
    }

    public addOrRemove(data: string, p: ProductInterface) {
        for (const color of p[data]) {
            if (!this.d[data].some(b => b.txt === color)) {
                this.d[data].push({
                    id: "was" + Math.round(Math.random() * 156133),
                    txt: color,
                    checked: false
                });
            }
        }
    }

    public getProducts(path: string = "") {
        if (path === `/collection/${this.d.activeSlug}`) {
            return;
        }
        this.d.activeSlug = path;
        this.d.searching = false;
        this.d.q = " ";
        this.d.q = "";
        this.resetAllfilters();
        // window.screenTop(0, 0);
        this.loadData(path);
        // console.log(this.d.q.length);
    }

    public checkIfReachedBottom() {
        window.onscroll = event => {
            this.d.scrollTop =
                document.documentElement.clientHeight +
                document.documentElement.scrollTop;

            // check if user has reached the end of page
            if (
                this.d.scrollTop >=
                    (document.querySelector(
                        "#component-container"
                    ) as HTMLDivElement).scrollHeight &&
                this.d.data.length &&
                "" !== this.d.nextUrl &&
                !this.d.loadingProducts
            ) {
                this.loadData(this.d.nextUrl, true);
                // console.log(this.d.nextUrl);
            }
        };
    }

    public setDoc(
        slug: string,
        isBack: boolean = false,
        append: boolean = false,
        isSearch: boolean = false
    ) {
        if (append) return;

        const method = isBack ? "replaceState" : "pushState";
        const slugSpace = slug.replace(/-/g, " ");
        const title = `LavaStore - ${slugSpace}`;
        let url = `/${this.lang()}/products/${slug}`;
        if (isSearch) {
            url = `/${this.lang()}/products/find?q=${encodeURI(slug)}`;
        }

        window.history[method](
            {
                slug
            },
            title,
            url
        );
        document.title = title;
        (document.querySelector(
            `#ptitle`
        ) as HTMLHeadingElement).textContent = title;
        // this.loadData(slug);
    }

    public addToCart() {
        // console.log('adding to cart product');
        const res = this.addToCartNative(
            this.d.mp,
            this.d.item.qty,
            this.d.item.size,
            this.d.item.color
        );
    }

    public removeFromCart(id: string) {
        this.removeFromCartNative(id);
    }

    public addToWish() {
        this.addToWishList(this.d, "mp").then(res => {
            if (res && res.item) {
                this.pushToCartList({ item: res.item });
            }
        });
    }

    public filterData(
        sort: string | "pop" | "rated" | "lowTo" | "highTo" = "pop",
        str: string
    ) {
        this.d.currentFilter = str;
        this.d.currentFilterIndx = sort;
        let uri = `/collection/${this.d.activeSlug}`;
        if (this.d.q.length) {
            this.d.searching = true;
            uri = `/collection/find?q=${this.d.q}`;
        }
        this.loadData(uri, false);
    }

    public setSideFilters(type: string, data: any) {
        // console.log(type, data);
        if (type === "size") {
            this.d.filters.sizes = [];
            (data as Param[]).forEach(x => this.d.filters.sizes.push(x.txt));
        } else if (type === "color") {
            this.d.filters.colors = [];
            (data as Param[]).forEach(x => this.d.filters.colors.push(x.txt));
        } else if (type === "price") {
            const min = this.d.selectedPrices.min;
            const max = this.d.selectedPrices.max;
            this.d.filters.price = {
                min: min <= max ? min : max,
                max: max >= min ? max : min
            };
        } else {
            this.d.filters.stars = data as number;
        }

        // console.log(this.d.filters);
        let uri = `/collection/${this.d.activeSlug}`;
        if (this.d.q.length) {
            this.d.searching = true;
            uri = `/collection/find?q=${this.d.q}`;
        }
        this.loadData(uri, false);
    }

    public searchFor() {
        if (!this.d.q || !this.d.q.length) return;
        this.d.searching = true;
        this.loadData(`/collection/find`);
    }

    public checkSearchForm() {
        const findPage = location.pathname.indexOf("/products/find") > -1;
        if (findPage) {
            this.d.q = location.href.split("q=")[1];
            this.searchFor();
            return;
        }
        this.loadData();
    }

    public resetAllfilters() {
        this.d.resetFilters = Math.round(Math.random() * 99999);
        this.d.filters = {
            sizes: [],
            colors: [],
            price: {
                min: 0,
                max: 0
            },
            stars: 0
        };
        this.loadData();
    }

    public hasFilterActive(): boolean {
        const f = this.d.filters;
        return (
            f.sizes.length > 0 ||
            f.colors.length > 0 ||
            f.price.min > 0 ||
            f.price.max > 0 ||
            f.stars > 0
        );
    }

    private showLoader(append: boolean = false) {
        this.d.loadingProducts = true;

        if (!append) {
            this.d.remain = 15;
            this.d.data = [];
        }
        this.fillLoadingData(this.d);
    }

    private hideLoader(append: boolean = false) {
        this.d.loadingProducts = false;
        this.d.loadingData = [];
    }

    beforeMount() {
        this.attachToGlobal(this, [
            "openModal",
            "getProducts",
            "showNotify",
            "pushToCartList",
            "addToCart",
            "removeFromCart",
            "addToWish",
            "filterData",
            "setSideFilters",
            "resetAllfilters",
            "hasFilterActive"
        ]);
        this.fillLoadingData(this.d);
    }

    mounted() {
        this.checkIfReachedBottom();

        this.$on("cartDone", _ => {
            this.d.cartLoaded = Math.random() * 10000;
        });

        this.checkSearchForm();

        window.onpopstate = e => {
            if (e.state) {
                // console.log(e.state);
                this.loadData(`/collection/${e.state.slug}`, false, true);
            }
        };
    }
}
