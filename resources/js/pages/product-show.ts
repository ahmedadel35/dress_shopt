import { Component, Mixins } from "vue-property-decorator";
import Super from "./super";
import ProductList from "./product-list";
import ProductInterface from "../interfaces/product";
import CartInterface from "../interfaces/cart";
import Axios, { AxiosResponse } from "axios";
import ProductMixin, { EMPTY_PRODUCT } from "../mixins/product-mixin";
import RateInterface from "../interfaces/rate";
import ProductShowMixin from "../mixins/product-show-mixin";

export interface UserRev {
    userId: number;
    id: number;
    index: number;
    rate: number;
    message: string;
    alreadyReved: boolean;
}

export interface Item {
    size: number;
    color: number;
    qty: number;
    wishId: number;
    wishing: boolean;
}

export interface ProductFeatData {
    loading: boolean;
    data: ProductInterface[];
    nextUrl: string;
    remain: number;
}

export interface ProductShowData {
    cart: CartInterface;
    cartLoader: boolean;
    wishLoader: boolean;
    q: string;
    product: ProductInterface;
    item: Item;
    pItem: Item;
    addingToCart: boolean;
    inCart: boolean;
    inWish: boolean;
    loadingRates: boolean;
    revData: RateInterface[];
    userRev: UserRev;
    savingRev: boolean;
    nextRevUrl: string;
    userCanUpdate: boolean;
    feat: ProductFeatData;
    mp: ProductInterface;
    related: ProductFeatData;
    emptyRates: boolean;
    revDataLoader: number[];
    userId: number;
    scrollTop: number;
}

@Component
export default class ProductShow extends Mixins(
    ProductMixin,
    ProductShowMixin
) {
    public d: ProductShowData = {
        cart: {
            items: [],
            wish: [],
            count: 0,
            total: 0
        },
        cartLoader: true,
        wishLoader: true,
        q: "",
        product: {
            id: 0,
            user_id: 0,
            category_slug: "",
            title: "",
            slug: "",
            price: 0,
            qty: 0,
            cartQty: 0,
            save: 0,
            info: "",
            sizes: [],
            colors: [],
            images: [],
            rates: [],
            rate_avg: 0,
            img_path: "",
            saved_price: 0,
            total: 0,
            totalInt: 0,
            created_at: "",
            updated_at: "",
            deleted_at: ""
        },
        item: {
            size: 0,
            color: 0,
            qty: 1,
            wishId: -10,
            wishing: true
        },
        pItem: {
            size: 0,
            color: 0,
            qty: 1,
            wishId: -10,
            wishing: true
        },
        addingToCart: false,
        inCart: false,
        inWish: false,
        loadingRates: false,
        revData: [],
        userRev: {
            userId: 0,
            id: 0,
            index: 0,
            rate: 0,
            message: "",
            alreadyReved: false
        },
        savingRev: false,
        nextRevUrl: "",
        userCanUpdate: false,
        feat: {
            loading: false,
            data: [],
            nextUrl: "",
            remain: 8
        },
        mp: EMPTY_PRODUCT,
        related: {
            loading: false,
            data: [],
            nextUrl: "",
            remain: 8
        },
        emptyRates: false,
        revDataLoader: [],
        userId: 0,
        scrollTop: 0,
    };
    public ratesRemain: number = 7;

    public loadProductData() {
        Axios.get(`/product/${this.slug}`, {
            params: { avg: "", pi: "" }
        }).then(res => {
            if (!res || !res.data) {
                this.error();
                return;
            }

            res.data.images = res.data.prod.images.map(x => {
                return {
                    id: "aww" + Math.round(Math.random() * 1889797),
                    src: x
                };
            });

            this.d.product = res.data.prod;
            this.d.product.rate_avg = res.data.rate_avg;
            // console.log(this.d.product);
            this.loadRates();
        });
    }

    public addToCart(item: string = "pItem", productInx: string = "product") {
        if (this.d.cartLoader || this.d.addingToCart) {
            return;
        }

        this.d.addingToCart = true;
        // console.log(this.d[item], item);
        const res = this.addToCartNative(
            this.d[productInx],
            this.d[item].qty,
            this.d[item].size,
            this.d[item].color
        );
        res.then((r: AxiosResponse) => {
            if (r && (r.data.item || r.data.updated)) {
                this.d.addingToCart = false;
            }
        }).finally(() => (this.d.addingToCart = false));
    }

    public addToWish(
        itemInx: string = "pItem",
        productInx: string = "product"
    ) {
        if (this.d.wishLoader) {
            return;
        }

        this.addToWishList(this.d, productInx, itemInx).then(res => {
            if (res && res.item) {
                this.pushToCartList({ item: res.item });
            }
        });
    }

    public loadRates(
        path: string = `/product/${this.pid}/rates`,
        append: boolean = false
    ) {
        this.d.loadingRates = true;
        this.fillRevDataLoader();
        Axios.get(path).then(res => {
            if (!res || !res.data) {
                this.error();
                return;
            }

            this.d.revDataLoader = [];

            if (!append) {
                if (!res.data.data.length) {
                    this.d.emptyRates = true;
                    this.d.loadingRates = false;
                    return;
                }

                this.d.revData = [...res.data.data];
                // check if user have abilty to delete any rates
                res.data.data.map(x => {
                    if (x.user_id !== this.userId) {
                        this.d.userCanUpdate = x.can_update;
                        return;
                    }
                });
                // console.log(this.d.userCanUpdate);
            } else {
                this.d.revData = [...this.d.revData.concat(res.data.data)];
                // console.log(this.d.revData);
            }

            this.setUserRev(res.data.data);
            this.d.nextRevUrl = res.data?.next_page_url || "";
            // @ts-ignore
            const remain = (res.total - res.to) / 7;
            this.ratesRemain = remain <= 7 ? remain : 7;
            this.d.loadingRates = false;
        });
    }

    public addRev() {
        this.d.savingRev = true;
        let method = "post",
            path = `/rates`;

        if (this.d.userRev.alreadyReved) {
            method = "patch";
            path = `/rates/${this.d.userRev.id}`;
        }

        const r = {
            user_id: this.userId,
            product_id: this.pid,
            rate: this.d.userRev.rate,
            message: this.d.userRev.message
        };
        if (!this.d.userRev.message.length) {
            delete r.message;
        }

        Axios[method](path, r).then(res => {
            if (
                !res ||
                !res.data ||
                (method === "post" && !res.data.rate) ||
                (method === "patch" && !res.data.updated)
            ) {
                this.d.savingRev = false;
                this.error();
                return;
            }

            if (!this.d.userRev.alreadyReved) {
                this.d.revData.unshift(res.data);
                this.d.userRev.id = parseInt(res.data.id);
                this.d.userRev.userId = this.userId;
            } else {
                const indx = this.d.revData.findIndex(
                    x => x.user_id === this.userId
                );
                this.d.revData[indx].rate = this.d.userRev.rate;
                this.d.revData[indx].message = this.d.userRev.message;
                this.d.revData[indx].updated = res.data.updated;
            }

            this.d.userRev.alreadyReved = true;

            this.success(this.getLang(2), this.getLang(5));

            this.d.savingRev = false;
        });
    }

    public loadMoreRevs() {
        this.loadRates(this.d.nextRevUrl, true);
    }

    public deleteRate(id: number, inx: number) {
        const deleteBtn = this.$root.$refs[
            "deleteRefBtn" + id
        ][0] as HTMLDivElement;

        if (deleteBtn && !deleteBtn.classList.contains("d-none")) {
            return;
        }
        deleteBtn.classList.remove("d-none");

        Axios.delete("/rates/" + id).then(res => {
            if (!res || res.status !== 204) {
                deleteBtn.classList.add("d-none");
                this.error();
                return;
            }

            // remove rev from revs array
            this.d.revData.splice(inx, 1);
            // remove user rev
            this.d.userRev = {
                userId: 0,
                id: 0,
                index: 0,
                rate: 0,
                message: "",
                alreadyReved: false
            };
            this.success();
        });
    }

    public loadFeaturedProds(
        path: string = "/collection/featured",
        append: boolean = false
    ) {
        this.loadFeaturedProdsNative(this.d, path, append);
    }

    public openModal(slug: string) {
        // console.log(slug);
        this.openModalNative(this.d, slug, "productShowModal", { avg: true });
    }

    public loadRelatedProds(
        path: string = `/product/${this.slug}/related`,
        append: boolean = false
    ) {
        if (this.d.related.loading || !path.length) {
            return;
        }

        this.d.related.loading = true;
        Axios.get(path).then(res => {
            if (!res.data.data) {
                this.error();
                return;
            }

            res = res.data;

            if (append) {
                this.d.related.data = this.d.related.data.concat([...res.data]);
                // setTimeout(_ => this.hideLoader(append), 5000);
            } else {
                this.d.related.data = [...res.data];
            }
            // @ts-ignore
            this.d.related.nextUrl = res?.next_page_url || "";
            // @ts-ignore
            const remain = Math.round((res.total - res.to) / 8);
            this.d.related.remain = remain <= 8 ? remain : 8;
            this.d.related.loading = false;
        });
    }

    private setUserRev(d: RateInterface[]) {
        const userId = this.userId;
        const indx = d.findIndex(x => x.user_id === userId);

        if (indx > -1) {
            const r = d[indx];
            console.log(r);
            this.d.userRev.index = indx;
            this.d.userRev.userId = userId;
            this.d.userRev.id = Number(r.id);
            this.d.userRev.rate = r.rate;
            this.d.userRev.message = (r.message as string) || "";
            this.d.userRev.alreadyReved = true;
            console.log(this.d.userRev);
        }
    }

    private fillRevDataLoader() {
        Array(this.ratesRemain)
            .fill(1)
            .forEach(x => this.d.revDataLoader.push(x));
    }

    get slug(): string {
        return window["xjs"].slug;
    }

    get userId(): number {
        return parseInt(window["xjs"].user_id);
    }

    get pid(): number {
        return parseInt(window["xjs"].pid);
    }

    beforeMount() {
        this.attachToGlobal(this, [
            "addToCart",
            "addToWish",
            "loadMoreRevs",
            "addRev",
            "deleteRate",
            "loadFeaturedProds",
            "loadRelatedProds",
            "openModal"
        ]);
    }

    mounted() {
        this.loadProductData();
        this.loadFeaturedProds();
        this.loadRelatedProds();
        this.fillRevDataLoader();

        this.$on("cartDone", () => {
            // check if product exists in cart
            this.checkIfProductExistsInCartOrWish(this.d, this.pid, "pItem");
        });
    }
}
