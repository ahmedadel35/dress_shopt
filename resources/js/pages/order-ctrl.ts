import { Component, Mixins } from "vue-property-decorator";
import Super from "./super";
import CartInterface from "../interfaces/cart";
import CartItemInterface from "../interfaces/cart-item";
import ProductInterface from "../interfaces/product";
import Axios from "axios";
import AddressInterface from "../interfaces/address";
import OrderInterface from "../interfaces/order";
import AddressMixin from "../mixins/address-mixin";

export interface Form {
    id?: number;
    userMail: string;
    firstName: string;
    lastName: string;
    address: string;
    dep: string;
    city: string;
    country: string;
    gov: string;
    postCode: string;
    phoneNumber: string;
    notes: string;
    check?: boolean;
}

export const FormInpts: AddressInterface = {
    id: 0,
    userMail: "",
    firstName: "",
    lastName: "",
    address: "",
    dep: "",
    city: "",
    country: "",
    gov: "",
    postCode: "",
    phoneNumber: "",
    notes: ""
};

export const EmptyOrder: OrderInterface = {
    id: 0,
    orderNum: "",
    user_id: 0,
    userMail: "",
    address_id: 0,
    status: "",
    total: 0,
    qty: 0,
    paymentMethod: "",
    paymentStatus: false
};

export interface OrderCtrlData {
    cart: CartInterface;
    cartLoader: boolean;
    wishLoader: boolean;
    q: string;
    form: Form;
    error: Form;
    continue: boolean;
    address: AddressInterface;
    order: OrderInterface;
    updaingOrder: boolean;
    paymentMethod: string;
    orderSuccess: boolean;
    userId: number;
    addresses: AddressInterface[];
    addressesLoader: number[];
    loadingAdresses: boolean;
    emptyAddressList: boolean;
    addingAddress: boolean;
    showForm: boolean;
    pageInx: number;
    scrollTop: number;
    hasErrors: boolean;
    deletingAddress: boolean;
    savingOrder: boolean;
    addressChecked: boolean;
}

@Component
export default class OrderCtrl extends Mixins(AddressMixin) {
    public d: OrderCtrlData = {
        cart: {
            items: [],
            wish: [],
            count: -5,
            total: 0
        },
        cartLoader: true,
        wishLoader: true,
        q: "",
        form: FormInpts,
        error: FormInpts,
        continue: false,
        address: FormInpts,
        order: EmptyOrder,
        updaingOrder: false,
        paymentMethod: "onDelivery",
        orderSuccess: false,
        userId: 0,
        addresses: [],
        addressesLoader: [1, 2, 3, 4],
        loadingAdresses: true,
        emptyAddressList: false,
        addingAddress: false,
        showForm: false,
        pageInx: 0,
        scrollTop: 0,
        hasErrors: false,
        deletingAddress: false,
        savingOrder: false,
        addressChecked: false
    };

    public addAddress() {
        this.d.hasErrors = this.validateData(this.d);
        if (this.d.continue || this.d.hasErrors || this.d.order.id) {
            return;
        }
        this.d.continue = true;

        // check if user edited old address then update
        if (this.d.address.id) {
            this.saveAddress(true).then(r => {
                this.success();
            });
            return;
        }

        // check for address validateion
        this.d.form.check = true;
        this.saveAddress().then(r => {
            if (r) {
                this.success();
            }
        });

        // .then(res => {
        //     this.d.continue = true;
        //     this.saveOrder();
        // });
    }

    public async loadAddresses() {
        this.d.loadingAdresses = true;

        const res = await Axios.get(`/user/${this.d.userId}/addresses`);

        if (!res || !res.data || !res.data.ads) {
            this.error();
            this.d.loadingAdresses = false;
            return;
        }

        if (!res.data.ads.length) {
            this.d.emptyAddressList = true;
            this.d.loadingAdresses = false;
            return;
        }

        this.d.addressesLoader = [];
        this.d.addresses = res.data.ads;
        this.d.form.userMail = res.data.userMail;
        this.d.error = Object.assign({}, this.resetFormObj);
        this.d.loadingAdresses = false;
    }

    public selectAddress(adr: AddressInterface) {
        // console.log('selected');
        this.d.error = Object.assign({}, this.resetFormObj);
        this.d.form = Object.assign({}, adr);
        this.d.address = adr;
        this.d.addressChecked = true;
    }

    public validateOnChange() {
        this.d.hasErrors = this.validateData(this.d);
    }

    public showAddressForm() {
        this.d.form = Object.assign({}, this.resetFormObj);
        this.d.address = Object.assign({}, this.resetFormObj);
        this.d.showForm = true;
    }

    public deleteAddress() {
        if (this.d.deletingAddress || !this.d.address.id) {
            return;
        }
        this.d.deletingAddress = true;

        Axios.delete(`/address/${this.d.address.id}`).then(res => {
            if (!res || res.status !== 204) {
                this.error(this.getLang(1), this.getLang(13));
                this.d.deletingAddress = false;
                return;
            }

            const inx = this.d.addresses.findIndex(
                x => x.id === this.d.address.id
            );
            this.d.addresses.splice(inx, 1);
            this.success();
            this.d.deletingAddress = false;
        });
    }

    public async saveAddress(update: boolean = false) {
        this.d.addressChecked = false;
        const res = await this.saveAddressNative(this.d, update);

        if (!res) {
            this.d.continue = false;
            this.d.savingOrder = false;
            return;
        }

        if (!update) {
            this.d.address = res;
            this.d.addresses.push(res);
        } else {
            this.d.addresses = [...this.d.addresses].map(x => {
                if (x.id === this.d.address.id) {
                    const obj = Object.assign({}, this.d.form);
                    delete obj.userMail;
                    obj.id = x.id;
                    // @ts-ignore
                    x = obj;
                }
                return x;
            });
        }

        this.d.hasErrors = false;
        this.d.addressChecked = true;
        this.d.continue = false;
        if (this.d.form.check) {
            // this.showTab("paymentMethod-tab");
        }
        return res;
    }

    public storeOrder() {
        if (this.d.savingOrder || !this.d.address.address) {
            return;
        }
        this.d.savingOrder = true;

        if (this.d.paymentMethod === "accept") {
            const items: CartItemInterface[] = [...this.d.cart.items];
            const address = this.d.form;
            // get iframe uri
            return Axios.post("/order/payment", {
                address,
                userMail: this.d.form.userMail,
                total: this.d.cart.total,
                qty: this.d.cart.count,
                paymentMethod: this.d.paymentMethod,
                items
            }).then(res => {
                if (!res || !res.data || !res.data.uri) {
                    this.d.savingOrder = false;
                    this.error();
                    return;
                }

                location.href = res.data.uri;
                // this.d.savingOrder = false;
            });
        }

        // save address first
        this.d.form.check = false;
        // check if address was selected then it was alwready saved
        if (this.d.address.id) {
            return this.saveOrder();
        }
        this.saveAddress()
            .then(res => {
                this.saveOrder();
            })
            .catch(err => {
                console.log(err.response);
                this.d.savingOrder = false;
            });
    }

    private async saveOrder() {
        const items: CartItemInterface[] = [...this.d.cart.items];
        // this.d.cart.items.forEach(x => {
        //     items.push({
        //         product_id: x.product_id,
        //         instance: x.instance,
        //         qty: x.qty,
        //         size: x.size,
        //         color: x.color,
        //         price: x.price,
        //         sub_total: x.sub_total,
        //         // @ts-ignore
        //         id: null
        //     });
        // });

        const res = await Axios.post("/order", {
            address_id: this.d.address.id,
            userMail: this.d.form.userMail,
            total: this.d.cart.total,
            qty: this.d.cart.count,
            paymentMethod: this.d.paymentMethod,
            items
        });

        if (!res || !res.data || res.status !== 200) {
            this.d.savingOrder = false;
            this.error();
            return;
        }

        this.d.savingOrder = false;
        this.d.order = res.data;
        this.d.order.id = res.data.enc_id;
        // clear cart list
        this.d.cart.items.splice(0);
        this.d.cart.total = 0;
        this.d.cart.count = 0;

        // go to current order id without reload
        // window.history["pushState"](
        //     {
        //         id: this.d.order.orderNum
        //     },
        //     document.title + `#${this.d.order.orderNum}`,
        //     `/${this.lang()}/order/${this.d.order.orderNum}`
        // );
        this.success();
    }

    public showTab(tabId: string) {
        const tab = document.getElementById(tabId) as HTMLLinkElement;
        if (tab) {
            // @ts-ignore
            new Tab(tab, { height: true }).show();
        }
    }

    // get resetFormObj(): AddressInterface {
    //     return {
    //         id: 0,
    //         userMail: "",
    //         firstName: "",
    //         lastName: "",
    //         address: "",
    //         dep: "",
    //         city: "",
    //         country: "",
    //         gov: "",
    //         postCode: "",
    //         phoneNumber: "",
    //         notes: ""
    //     };
    // }

    beforeMount() {
        this.attachToGlobal(this, [
            "addAddress",
            "updateOrder",
            "selectAddress",
            "addAddress",
            "addOrderWithoutAddress",
            "validateOnChange",
            "showAddressForm",
            "deleteAddress",
            "showTab",
            "storeOrder"
        ]);
    }

    mounted() {
        this.$on("cartDone", () => {
            if (this.d.userId) {
                // user logged in
                this.loadAddresses();
            } else {
                this.d.showForm = true;
                this.d.addressesLoader = [];
            }
        });

        this.d.form.country = "eg";
        this.d.form.gov = "ca";
    }
}
