import { Component, Mixins } from "vue-property-decorator";
import Super from "./super";
import CartInterface from "../interfaces/cart";
import CartItemInterface from "../interfaces/cart-item";
import Axios from "axios";
import AddressInterface from "../interfaces/address";
import { FormInpts } from "./order-ctrl";
import AddressMixin from "../mixins/address-mixin";
import { addListener } from "cluster";

export interface UserCtrlData {
    cart: CartInterface;
    cartLoader: boolean;
    wishLoader: boolean;
    q: string;
    userId: number;
    scrollTop: number;
    items: CartItemInterface[];
    orderId: string;
    creating: boolean;
    form: AddressInterface;
    error: AddressInterface;
    address: AddressInterface;
    hasErrors: boolean;
    addressChecked: boolean;
    profile: {
        img: HTMLInputElement;
        prev: string;
        hasErr: boolean;
        errors: {
            img: string[];
        };
        uploading: boolean;
    };
    completing: boolean;
}

@Component
export default class UserCtrl extends Mixins(AddressMixin) {
    public d: UserCtrlData = {
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
        scrollTop: 0,
        items: [],
        orderId: "",
        creating: false,
        form: FormInpts,
        error: FormInpts,
        address: FormInpts,
        hasErrors: false,
        addressChecked: false,
        profile: {
            // @ts-ignore
            img: {},
            prev: "",
            hasErr: false,
            errors: { img: [] },
            uploading: false
        },
        completing: false
    };

    public openItemsModal(data: string, orderId: string) {
        this.d.items = [...JSON.parse(data || "[]")];
        // console.log(JSON.parse(data || "[]"));
        this.d.orderId = orderId;
        const modal = document.getElementById("items-modal") as HTMLDivElement;
        if (modal) {
            // @ts-ignore
            new Modal(modal).show();
        }
    }

    public createAddress(close: boolean = false, update: boolean = false) {
        // console.log('asdasdasd');
        const modal = document.getElementById(
            "address-form-modal"
        ) as HTMLDivElement;
        this.d.form = update ? this.d.address : this.resetFormObj;
        this.d.error = this.resetFormObj;
        // console.log(modal);
        if (modal) {
            if (close) {
                // @ts-ignore
                return new Modal(modal).hide();
            }
            // @ts-ignore
            new Modal(modal).show();
        }
    }

    public saveAddress() {
        this.d.hasErrors = this.validateData(this.d);
        if (this.d.creating || this.d.hasErrors) {
            return;
        }
        console.log(this.d.address);
        const update = (this.d.address.id as number) > 0;
        this.d.creating = true;
        this.saveAddressNative(this.d, update, { withTrans: true }).then(
            res => {
                if (
                    !res ||
                    (!update && !res.address) ||
                    (update && res.address)
                ) {
                    this.d.creating = false;
                    this.error();
                    return;
                }

                if (!update) {
                    this.createAddressElem(res);
                } else {
                    location.reload();
                }

                this.createAddress(true);
                this.d.address = this.resetFormObj;
                this.d.creating = false;
                this.d.form = this.resetFormObj;
                (document.querySelector(
                    "#emptyMess"
                ) as HTMLDivElement).classList.add("d-none");
            }
        );
    }

    public editAddress(data: string) {
        // this.d.address = this.resetFormObj;
        this.d.address = JSON.parse(data || "{}");
        this.createAddress(false, true);
    }

    public deleteAddress(addId: number) {
        const loader = document.querySelector(
            `#spinnerDelete${addId}`
        ) as HTMLDivElement;
        if (loader && !loader.classList.contains("d-none")) {
            return;
        }
        this.showLoader(`spinnerDelete${addId}`);
        Axios.delete(`/address/${addId}`).then(res => {
            if (!res || res.status !== 204) {
                this.error();
                this.hideLoader(`spinnerDelete${addId}`);
                return;
            }

            this.hideLoader(`address${addId}`);
            this.hideLoader(`spinnerDelete${addId}`);
        });
    }

    public validateOnChange() {
        this.d.hasErrors = this.validateData(this.d);
    }

    public previewImg(ev) {
        const inp: HTMLInputElement = ev.target;
        if (!inp.files || !inp.files[0]) {
            this.d.profile.prev = "";
            return;
        }
        this.d.profile.img = inp;

        const reader = new FileReader();

        reader.onload = e => {
            this.d.profile.prev = (e.target as any).result;
        };

        reader.readAsDataURL((inp.files as FileList)[0]);
    }

    public uploadImage() {
        if (
            this.d.profile.uploading ||
            !this.d.profile.img?.files ||
            !this.d.profile.img?.files[0]
        ) {
            // return;
        }
        this.d.profile.uploading = true;
        const form = new FormData();
        form.append("img", (this.d.profile.img.files as FileList)[0]);

        Axios.post("/user/profile/image", form, {
            headers: {
                "Content-Type": "multipart/form-data"
            }
        }).then(res => {
            if (!res || !res.data) {
                this.error();
                this.d.profile.uploading = false;
                return;
            }

            if (res.status === 422) {
                this.d.profile.hasErr = true;
                this.d.profile.errors.img = res.data.errors.img;
                this.d.profile.uploading = false;
                return;
            }

            this.d.profile.uploading = false;
            this.success();
            this.d.profile.prev = "";
            // @ts-ignore
            this.d.profile.img = {};
            this.updateBgImage(".nav-right .x-pimg", res.data.img);
            this.updateBgImage(".navbar .x-pimg", res.data.img);
            this.updateBgImage(".sidebar .x-pimg", res.data.img);
        });
    }

    public deleteRate(rid: number) {
        const spinner = document.getElementById(
            `spinner${rid}`
        ) as HTMLDivElement;
        const rate = document.getElementById(`rateid${rid}`) as HTMLDivElement;
        if (
            (spinner.parentElement as HTMLElement).classList.contains(
                "disabled"
            )
        ) {
            // return;
        }
        (spinner.parentElement as HTMLElement).classList.add("disabled");
        spinner.classList.remove("d-none");

        Axios.delete(`/rates/${rid}`).then(res => {
            if (!res || res.status !== 204) {
                this.error();
                (spinner.parentElement as HTMLElement).classList.remove(
                    "disabled"
                );
                spinner.classList.add("d-none");
                return;
            }

            (spinner.parentElement as HTMLElement).classList.remove("disabled");
            spinner.classList.add("d-none");
            this.success();
            // remove rate element
            (rate.parentElement as HTMLElement).classList.add("d-none");
        });
    }

    private updateBgImage(selector: string, imgUrl: string) {
        const el = document.querySelector(selector) as HTMLDivElement;
        if (el) {
            el.style.backgroundImage = `url(/${imgUrl})`;
        }
    }

    private createAddressElem(address: AddressInterface) {
        const parent = document.createElement("div") as HTMLDivElement;
        parent.id = `address${address.id}`;
        parent.classList.add("col-md-6", "mb-3");
        const child = document.createElement("address");
        child.innerHTML = `<div class='card card-body'><h5>${
            address.firstName
        } ${address.lastName}</h5> (${
            typeof address.dep === "number" ? address.dep : ""
        }) ${address.address} ${address.city}<br> <strong>${address.gov}, ${
            address.country
        }</strong><br>
        <a class="mt-2" href="tel:${address.phoneNumber}">
    <i class="fas fa-phone-alt mx-1"></i>
    ${address.phoneNumber}
</a></div>`;
        parent.append(child);
        const addressList = document.querySelector(
            `#address-list-row`
        ) as HTMLDivElement;
        // parent.setAttribute('v-on:click', 'h.d.sdwsdsa()');
        addressList.prepend(parent);
        // this.$forceUpdate();
    }

    private showLoader(id: string) {
        const loader = document.querySelector(`#${id}`) as HTMLDivElement;
        if (loader) {
            loader.classList.remove("d-none");
        }
    }

    private hideLoader(id: string) {
        const loader = document.querySelector(`#${id}`) as HTMLDivElement;
        if (loader) {
            loader.classList.add("d-none");
        }
    }

    public completeOrder(id: string) {
        const spinner = document.querySelector(
            `#spinord${id}`
        ) as HTMLDivElement;
        const btn = spinner.parentElement as HTMLElement;
        if (!spinner.classList.contains("d-none")) {
            return;
        }
        btn.classList.add("disabled");
        spinner.classList.remove("d-none");

        Axios.post(`/track/${id}/complete`).then(res => {
            if (!res || !res.data || !res.data.updated) {
                this.error();
                btn.classList.remove("disabled");
                spinner.classList.add("d-none");
                return;
            }

            btn.classList.remove("disabled");
            spinner.classList.add("d-none");
            this.success();
            btn.classList.add("d-none");
        });
    }

    get resetFormObj(): AddressInterface {
        return {
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
    }

    beforeMount() {
        this.attachToGlobal(this, [
            "openItemsModal",
            "editAddress",
            "deleteAddress",
            "createAddress",
            "saveAddress",
            "validateOnChange",
            "previewImg",
            "uploadImage",
            "deleteRate",
            "completeOrder"
        ]);
    }
}
