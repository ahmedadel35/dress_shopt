import { Vue, Component } from "vue-property-decorator";
import CartInterface from "../interfaces/cart";
import Axios from "axios";
import ProductInterface from "../interfaces/product";
import CartItemInterface from "../interfaces/cart-item";
import SideBar from "../components/sidebar.vue";

export interface SuperData {
    // notifyPos: string;
    cart: CartInterface;
    cartLoader: boolean;
    wishLoader: boolean;
    q: string;
    userId: number;
    scrollTop: number;
}

@Component({
    template: require("./index-template.html")
})
export default class Super extends Vue {
    public d: SuperData = {
        cart: {
            items: [],
            // @ts-ignore
            loaders: [1, 2, 3, 4],
            wish: [],
            count: 0,
            total: 0
        },
        cartLoader: true,
        wishLoader: true,
        q: "",
        userId: 0,
        scrollTop: 0
    };
    public notifyPos: string = "bottom right";
    public formatter = new Intl.NumberFormat("en-EG");
    public cartLoading: boolean = false;
    public addingToWish: boolean = false;

    /**
     * attach compoenent properties and methods to global d variable
     *
     * @param self current component instance
     * @param methods array of public methods
     */
    protected attachToGlobal(self: Super, methods: string[]) {
        for (const k in self.$data) {
            if (k === "d") {
                continue;
            }
            this.d[k] = this.$data[k];
        }

        methods.map(x => {
            this.d[x] = self[x];
        });
    }

    protected addClass(selector: string, cls: string) {
        const el = document.querySelector(selector) as HTMLElement;
        if (!el) return;
        el.classList.add(cls);
    }

    protected removeClass(selector: string, cls: string) {
        const el = document.querySelector(selector) as HTMLElement;
        if (!el) return;
        el.classList.remove(cls);
    }

    protected isTouchScreen() {
        try {
            document.createEvent("TouchEvent");
            return true;
        } catch (e) {
            return false;
        }
    }

    protected extractSlug(): string {
        let arr = document.location.pathname.split("/");

        return arr[3];
    }

    protected removeItem(type: string = "cart", item: CartItemInterface) {
        // console.log(type, item);

        const hideLoader = () => {
            if (type === "wish") {
                this.hideWishLoaderNative();
            } else {
                this.hideCartLoader();
            }
        };
        if (type === "wish") {
            this.showWishLoaderNative();
        } else {
            this.showCartLoader();
        }

        const data = type === "wish" ? { wish: true } : {};
        Axios.post(`/cart/${item.id}/delete`, data).then(res => {
            if (!res || res.status !== 204) {
                this.error();
                hideLoader();
                return;
            }

            // remove from cart array
            const list = type === "wish" ? "wish" : "items";
            const inx = (this.d.cart[list] as CartItemInterface[]).findIndex(
                x => x.id === item.id
            );
            (this.d.cart[list] as CartItemInterface[]).splice(inx, 1);

            if (type === "cart") {
                (this.d.cart.count as number) -= item.qty;
                (this.d.cart.total as number) -= item.sub_total;
            }

            this.$emit("cartDone", true);
            hideLoader();
        });
    }

    public showCartLoader(
        id: number | null = null,
        instance: string | null = null
    ) {
        const spinner = document.getElementById(
            "spinnerLoader" + id
        ) as HTMLSpanElement;
        if (spinner && null === instance) {
            spinner.classList.remove("d-none");
        }
        // console.log("showing");
        this.d.cartLoader = true;
        // (this.$root.$refs.cartLoader as HTMLElement)?.classList.remove(
        //     "d-none"
        // );
        this.cartLoading = true;
    }

    public showWishLoaderNative() {
        this.d.wishLoader = true;
    }

    public hideCartLoader(
        id: number | null = null,
        instance: string | null = null
    ) {
        const spinner = document.getElementById(
            "spinnerLoader" + id
        ) as HTMLSpanElement;
        if (spinner && null === instance) {
            spinner.classList.add("d-none");
        }
        this.d.cartLoader = false;
        // this.$root.$refs.
        // (this.$root.$refs.cartLoader as HTMLElement[]).forEach(x => {
        //     x?.classList.add("d-none");
        // })
        this.cartLoading = false;
    }

    public hideWishLoaderNative() {
        this.d.wishLoader = false;
    }

    protected showWishLoader(productId: number) {
        this.addingToWish = true;
        this.addClass("love-btn" + productId, "active alive");
    }

    protected dir(): string {
        return document.dir;
    }

    protected lang(): string {
        return document.documentElement.lang;
    }

    protected notify(
        title: string,
        text: string = "",
        type: string = "",
        duration: number = 3200,
        speed: number = 300,
        closeOnCLick: boolean = true
    ) {
        if (this.dir() === "rtl") {
            this.notifyPos = "bottom left";
            type += " text-left";
        }

        // @ts-ignore
        this.$notify({
            // @ts-ignore
            title,
            // @ts-ignore
            text,
            // @ts-ignore
            type,
            // @ts-ignore
            duration,
            // @ts-ignore
            speed,
            // @ts-ignore
            closeOnCLick
        });
    }

    protected error(
        title: string = this.getLang(1),
        text: string = this.getLang(4)
    ) {
        this.notify(title, text, "error");
    }

    protected warn(title: string = this.getLang(3), text: string = "") {
        this.notify(title, text, "warn");
    }

    protected success(title: string = this.getLang(2), text: string = "") {
        this.notify(title, text, "success");
    }

    protected info(title: string = this.getLang(0), text: string = "") {
        this.notify(title, text, "info");
    }

    /**
     *
     * @param inx
     * @tutorial 0 => alert title
     * @tutorial 1 => error title
     * @tutorial 2 => success title
     * @tutorial 3 => warn title
     * @tutorial 4 => error message
     * @tutorial 5 => revSuccess message
     * @tutorial 6 => cart products error
     * @tutorial 7 => cart product exists
     * @tutorial 8 => quick view btn text
     * @tutorial 9 => add to cart btn text
     * @tutorial 10 => you save text
     * @tutorial 11 => offTxt
     * @tutorial 12 => email success
     */
    protected getLang(inx: number): string {
        return window["xjs"].xlang[inx] || "";
    }

    protected getVar(key: string): any {
        return window["xjs"][key];
    }

    protected loadCartItemsNative() {
        this.showCartLoader();
        this.showWishLoaderNative();

        Axios.get("/cart").then(res => {
            if (!res || !res.data) {
                this.error();
                this.hideCartLoader();
                this.hideWishLoaderNative();
                return;
            }

            // @ts-ignore
            this.d.cart.loaders = [];
            this.d.cart = res.data;
            this.d.userId = res.data.userId;

            // check if cart qty is less than product qty
            const found = this.d.cart.items.some(x => x.qty > x.product!.qty);
            if (found) {
                this.error(this.getLang(1), this.getLang(6));
            }
            this.hideCartLoader();
            this.hideWishLoaderNative();
            this.$emit("cartDone", true);
        });
    }

    public formatNum(num: string) {
        return this.formatter.format(parseFloat(num));
    }

    public openSide(ref: string) {
        (this.$root.$refs[ref] as SideBar)!.toggle();
    }

    public addClassRemoveFromAll(
        ev: Event,
        cls: string,
        toAdd: string = "active"
    ) {
        const toRemove = document.querySelectorAll(`.${cls}`);
        if (toRemove && toRemove.length) {
            // console.log(toRemove);
            toRemove.forEach((x: HTMLElement) => x.classList.remove(toAdd));
        }
        (ev.target as HTMLElement)?.classList?.toggle(toAdd);
    }

    beforeMount() {
        this.attachToGlobal(this, [
            "formatNum",
            "removeItem",
            "openSide",
            "addClassRemoveFromAll",
            "searchFor",
            "showCartLoader",
            "hideCartLoader",
            "showWishLoaderNative",
            "hideWishLoaderNative"
        ]);
    }

    mounted() {
        this.loadCartItemsNative();
        // add page direction to body
        (document.body as HTMLBodyElement).classList.add(this.dir());

        window.onscroll = event => {
            this.d.scrollTop = document.documentElement.scrollTop;
        };

        try {
            // @ts-ignore
            if (BSN) {
                // @ts-ignore
                BSN.initCallback(document.getElementById("app"));
            } else {
                setTimeout(_ => {
                    // @ts-ignore
                    if (BSN) {
                        // @ts-ignore
                        BSN.initCallback(document.getElementById("app"));
                    }
                }, 700);
            }
        } catch (e) {}
    }
}
