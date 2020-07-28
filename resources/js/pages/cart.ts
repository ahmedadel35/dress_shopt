import { Component, Mixins } from "vue-property-decorator";
import Super from "./super";
import CartInterface from "../interfaces/cart";
import CartItemInterface from "../interfaces/cart-item";
import ProductMixin from "../mixins/product-mixin";
import ProductInterface from "../interfaces/product";
import Axios from "axios";
import AddressInterface from "../interfaces/address";
import OrderInterface from '../interfaces/order';


export interface CartData {
    cart: CartInterface;
    cartLoader: boolean;
    wishLoader: boolean;
    q: string;
    loadingData: number[];
    userId: number;
    scrollTop: number;
}

@Component
export default class Cart extends Mixins(ProductMixin) {
    public d: CartData = {
        cart: {
            items: [],
            wish: [],
            count: -5,
            total: 0
        },
        cartLoader: true,
        wishLoader: true,
        q: "",
        loadingData: [1, 2, 3, 4, 5, 6],
        userId: 0,
        scrollTop: 0
    };

    public removeItemFromCart(c: CartItemInterface, id: string) {
        this.showBtnLoader(id);

        this.removeItem("cart", c);
    }

    public updateCartItem(c: CartItemInterface, id: string) {
        this.showBtnLoader(id);
        this.updateCartNative(
            c.product as ProductInterface,
            c.qty,
            c.size,
            c.color,
            c
        ).then(res => {
            this.hideBtnLoader(id);
        });
    }

    private showBtnLoader(id: string) {
        const spinner = document.querySelector(`#${id}`) as HTMLSpanElement;
        if (spinner) {
            spinner.classList.remove("d-none");
        }
    }

    private hideBtnLoader(id: string) {
        const spinner = document.querySelector(`#${id}`) as HTMLSpanElement;
        if (spinner) {
            spinner.classList.add("d-none");
        }
    }

    beforeMount() {
        this.attachToGlobal(this, [
            "removeItemFromCart",
            "updateCartItem",
            "addOrder",
            "updateOrder"
        ]);
    }

    mounted() {
        this.$on("cartDone", _ => {
            this.d.loadingData = [];
        });
    }
}
