import { Vue, Component, Prop, Watch } from "vue-property-decorator";
import Super from "../pages/super";
import { ProductListData } from "../pages/product-list";
import { ProductShowData } from "../pages/product-show";
import CartInterface from "../interfaces/cart";
import Axios, { AxiosResponse } from "axios";
import ProductInterface from "../interfaces/product";
import CartItemInterface from "../interfaces/cart-item";
import { HomeData } from '../pages/home';
import { SuperData } from '../pages/super';
import AdminCtrl from '../pages/admin-ctrl';
import { AdminCtrlData } from '../pages/admin-ctrl';
import { AdminProductListData } from '../pages/admin-product-list';

export const EMPTY_PRODUCT: ProductInterface = {
    id: 0,
    user_id: 0,
    category_slug: "",
    title: "",
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
};

@Component
export default class ProductMixin extends Super {
    public checkIfProductExistsInCartOrWish(
        self: ProductShowData,
        id: number = self.product.id,
        itemInx: string = "item"
    ) {
        // console.log(self, id, itemInx);
        // check if item exists in cart list
        const pinx = self.cart.items.findIndex(i => i.product_id === id || 0);
        if (pinx > -1) {
            self.inCart = true;
            const item = self.cart.items[pinx];
            // console.log(item);
            // self[itemInx].qty = item.qty;
            self[itemInx] = {
                qty: item.qty,
                size: item.size,
                color: item.color,
                wishId: -10,
                wishing: false
            };
        } else {
            self.inCart = false;
            self[itemInx].wishId = 0;
        }

        // check if item exists in wish list
        const wishListId = self.cart.wish.findIndex(
            wi => wi.product_id === id || 0
        );
        if (wishListId > -1) {
            self.inWish = true;
            self[itemInx].wishId = self.cart.wish[wishListId].id;
            self[itemInx].wishing = false;
        } else {
            self.inWish = false;
            self[itemInx].wishId = 0;
            self[itemInx].wishing = false;
        }
        // console.log(pinx, self[itemInx]);
    }

    protected async addToCartNative(
        product: ProductInterface,
        qty: number = 1,
        size: number = 0,
        color: number = 0,
        instance: string | null = null
    ): Promise<AxiosResponse | {} | undefined> {
        // console.log('adding to cart');
        this.showCartLoader(product.id, instance);
        // check if product have any amount left
        if (
            product.qty < 1 ||
            qty > product.qty ||
            size > product.sizes.length ||
            color > product.colors.length
        ) {
            this.error();
            this.hideCartLoader(product.id, instance);
            return;
        }

        // check if item already added to cart
        const pinx = this.d.cart.items.findIndex(
            i => i.product_id === product.id && i.instance === instance
        );
        if (pinx > -1 && null === instance) {
            const item = this.d.cart.items[pinx];
            // item already in cart list
            // THEN update current cart if any changes

            // check if no changes was made to item
            if (
                item.qty === qty &&
                item.size === size &&
                item.color === color
            ) {
                this.hideCartLoader(product.id, instance);
                return;
            }

            const updateRes = await this.updateCartNative(
                product,
                qty,
                size,
                color,
                item
            );
            return updateRes;
        }
        // item not exists in cart list
        // Then save it
        const res = await Axios.post(`/cart/${product.slug}`, {
            qty,
            size,
            color
        });

        if (!res || !res.data) {
            this.error();
            this.hideCartLoader(product.id, instance);
            return { errors: true };
        }

        if (res.data.exists) {
            this.warn(this.getLang(0), this.getLang(7));
            this.hideCartLoader(product.id, instance);
            return { exists: true };
        }
        // console.log(res.data);

        this.d.cart.items.push(res.data.item);
        (this.d.cart.count as number) += res.data.item.qty;
        (this.d.cart.total as number) += res.data.item.sub_total;

        this.success(this.getLang(2), "");

        this.hideCartLoader(product.id, instance);
        this.$emit('cartDone', true);
        return res;
    }

    protected async updateCartNative(
        product: ProductInterface,
        qty: number = 1,
        size: number = 0,
        color: number = 0,
        item: CartItemInterface
    ): Promise<AxiosResponse | undefined> {
        this.showCartLoader(product.id);

        const res = await Axios.put(`/cart/${product.slug}/${item.id}`, {
            qty,
            size,
            color
        });

        if (!res || !res.data) {
            this.error();
            this.hideCartLoader(product.id);
            return;
        }

        if (res.data.exists) {
            this.warn(this.getLang(0), this.getLang(7));
            this.hideCartLoader(product.id);
            return;
        }

        if (!res.data.updated) {
            this.error();
            this.hideCartLoader(product.id);
            return;
        }

        let total = 0,
            count = 0;

        this.d.cart.items = this.d.cart.items.map(i => {
            if (i.id === item.id) {
                i.qty = qty;
                i.size = size;
                i.color = color;
                i.sub_total = parseFloat((qty * i.price).toFixed(2));
            }
            total += i.sub_total;
            count += i.qty;
            return i;
        });

        // update cart count and total price
        this.d.cart.total = parseFloat(total.toFixed(2));
        this.d.cart.count = count;

        this.success(this.getLang(2), "");
        this.hideCartLoader(product.id);
        return res;
    }

    protected async addToWishNative(
        product: ProductInterface,
        in_wish: boolean = false,
        itemId: number = 0
    ) {
        if (this.addingToWish) {
            return;
        }
        this.showWishLoader(product.id);
        if (in_wish) {
            const deleting = await Axios.post(`/cart/${itemId}/delete`, {
                wish: true
            });

            if (!deleting || deleting.status !== 204) {
                this.addingToWish = false;
                this.error();
                return { errors: true };
            }

            this.addingToWish = false;
            // this.success(this.getLang(2), "");
            this.removeFromCartNative(itemId);
            return { done: true };
        }

        const res = await Axios.post("/cart/" + product.slug, {
            qty: product.qty,
            size: 0,
            color: 0,
            wish: true
        });

        if (!res || !res.data || res.data.errors) {
            this.error();
            return { errors: true };
        }

        if (res.data.exists) {
            this.warn(this.getLang(0), this.getLang(7));
            // this.addingToCart = false;
            // in_wish = true;
            return { exists: true };
        }

        this.addingToWish = false;
        // in_wish = true;
        // this.itemId.wish = res.data.item.id;
        this.success(this.getLang(2), "");
        return res.data;
    }

    public removeFromCartNative(id: string | number) {
        const indx = this.d.cart.wish.findIndex(
            x => x.id === parseInt(id as string)
        );
        if (indx > -1) {
            this.d.cart.wish.splice(indx, 1);
            this.success(this.getLang(2), "");
            this.$emit('cartDone', true);
        }
    }

    public async addToWishList(
        self: ProductListData | ProductShowData | HomeData,
        product: string = "product",
        itemInx: string = "item"
    ) {
        // console.log(self, itemInx);
        self[itemInx].wishing = true;
        let inWish = false;
        if (product === "product") {
            inWish = (self as ProductShowData).inWish;
        } else {
            inWish = self[itemInx].wishId > -1;
        }

        const res = await this.addToWishNative(
            self[product],
            inWish,
            self[itemInx].wishId
        );

        if (!res || res.errors) {
            self[itemInx].wishing = false;
            return;
        }

        if (res.exists) {
            self[itemInx].wishing = false;
            self[itemInx].wishId = Math.random() * 100;
            return;
        }

        self[itemInx].wishId = res.item?.id ?? -1;
        if (product === "product") {
            (self as ProductShowData).inWish = res.done ? false : true;
        }
        self[itemInx].wishing = false;
        return res;
    }

    public pushToCartList(
        // self: ProductListData | ProductShowData,
        ev: { item: CartItemInterface }
    ) {
        // console.log("hererer");
        // console.log(ev.item);
        if (ev.item.instance === "wish") {
            this.d.cart.wish.push(ev.item);
            this.$emit('cartDone', true);
            return;
        }
        (this.d.cart.items as CartItemInterface[]).push(ev.item);
        // update cart total and count
        (this.d.cart.total as number) += ev.item.sub_total;
        (this.d.cart.count as number) += ev.item.qty;
    }

    protected async openModalNative(
        self: ProductListData | ProductShowData | HomeData | AdminProductListData,
        slug: string,
        modalId: string = "productModal",
        params: {
            avg?: boolean;
            pi?: boolean;
        } = {}
    ) {
        const modal = document.querySelector(`#${modalId}`) as HTMLDivElement;

        // reset current product data
        self.mp = EMPTY_PRODUCT;
        // reset item
        self.item = { qty: 1, size: 0, color: 0, wishId: -5, wishing: false };

        // get product from current data
        let p: ProductInterface = EMPTY_PRODUCT;
        if (modalId === "productModal") {
            p = (self as ProductListData).data.filter(x => x.slug === slug)[0];
        } else {
            p = (self as ProductShowData).product;
        }

        // @ts-ignore
        new Modal(modal).show();

        // check if item exists in cart list
        const pinx = self.cart.items.findIndex(
            i => (i.product as ProductInterface)?.slug === slug || 0
        );
        if (pinx > -1) {
            const item = self.cart.items[pinx];
            // console.log(item);
            self.item = {
                qty: item.qty,
                size: item.size,
                color: item.color,
                wishId: 0,
                wishing: false
            };
        }

        // check if item exists in wish list
        let wishListId = self.cart.wish.findIndex(
            wi => (wi.product as ProductInterface)?.slug === slug || 0
        );
        if (wishListId > -1) {
            wishListId = self.cart.wish[wishListId].id;
        }

        // load new product data
        Axios.get("/product/" + slug, { params }).then(res => {
            if (
                !res ||
                !res.data ||
                (!params.avg && !res.data.title) ||
                (params.avg && !res.data.prod)
            ) {
                this.error();
                // close modal
                // @ts-ignore
                new Modal(modal).hide();
                return;
            }

            const d = params.avg
                ? (res.data.prod as ProductInterface)
                : (res.data as ProductInterface);

            // add random id to images
            d.images = d.images.map(x => {
                return {
                    id: Math.random() * 100,
                    src: x
                };
            }) as any;

            // update product price
            d.priceInt = d.price as number;
            d.saved_priceInt = d.saved_price as number;
            d.price = this.formatter.format(d.price as number);
            d.saved_price = this.formatter.format(d.saved_price as number);

            // add rates from curent data
            if (params.avg) {
                d.rate_avg = res.data.rate_avg;
                d.rates = [];
            } else {
                d.rate_avg = p.rate_avg;
                d.rates = p.rates;
            }

            d.total = d.totalInt = 0;

            self.item.wishId = wishListId;

            // setTimeout(_ => (self.mp = d), 4000);
            self.mp = d;

            if (d.qty < 1) {
                self.mp.cartQty = 5;
            }
        });
    }

    protected showNotifyNative(inx: number = 0) {
        if (inx === 0) {
            this.warn(this.getLang(0), this.getLang(7));
            return;
        }
        this.error();
    }

    mounted() {
        this.attachToGlobal(this, [
            "showNotifyNative",
            "pushToCartList",
            "removeFromCartNative"
        ]);
    }
}
