import { Component, Mixins } from "vue-property-decorator";
import Super from "./super";
import CartInterface from "../interfaces/cart";
import CartItemInterface from "../interfaces/cart-item";
import ProductMixin from "../mixins/product-mixin";
import ProductInterface from "../interfaces/product";
import { EMPTY_PRODUCT } from "../mixins/product-mixin";
import Axios from "axios";

export interface AdminCtrlData {
    cart: CartInterface;
    cartLoader: boolean;
    wishLoader: boolean;
    q: string;
    userId: number;
    scrollTop: number;
    items: CartItemInterface[];
    orderId: string;
    cform: {
        title: string;
        error: string[];
        saving: boolean;
    };
    editingCat: boolean;
    action: string;
    editingSub: boolean;
    actionSub: string;
    prev: string;
    editingTag: boolean;
    actionTag: string;
    prevArr: string[];
}

@Component
export default class AdminCtrl extends Super {
    public d: AdminCtrlData = {
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
        cform: {
            title: "",
            error: [],
            saving: false
        },
        editingCat: false,
        action: "",
        editingSub: false,
        actionSub: "",
        prev: "",
        editingTag: false,
        actionTag: "",
        prevArr: []
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

    public updateRole(id: string, role: string, enc_id: string) {
        const spinner = document.getElementById(id) as HTMLDivElement;
        const btn = spinner.parentElement as HTMLElement;
        if (!spinner.classList.contains("d-none")) {
            return;
        }
        spinner.classList.remove("d-none");
        btn.classList.add("disabled");

        Axios.patch(`/users/${enc_id}/role`, { role }).then(res => {
            if (!res || !res.data || !res.data.updated) {
                this.error();
                spinner.classList.add("d-none");
                btn.classList.remove("disabled");
                return;
            }

            spinner.classList.add("d-none");
            btn.classList.remove("disabled");
            this.success();
        });
    }

    public completeOrder(id: string) {
        const spinner = document.getElementById(
            `spinord${id}`
        ) as HTMLDivElement;
        const btn = spinner.parentElement as HTMLElement;
        const icon = document.getElementById(`icon${id}`) as HTMLSpanElement;
        if (!spinner.classList.contains("d-none")) {
            return;
        }
        spinner.classList.remove("d-none");
        btn.classList.add("disabled");

        const done = { done: true };

        Axios.patch(`/orders/${id}/complete`, done).then(res => {
            if (!res || !res.data || !res.data.updated) {
                this.error();
                spinner.classList.add("d-none");
                btn.classList.remove("disabled");
                return;
            }

            spinner.classList.add("d-none");
            btn.classList.remove("disabled");
            btn.classList.add("d-none");
            // console.log(btn);
            icon.classList.add("fa-check", "text-success");
            this.success();
        });
    }

    // public saveCat(sub: boolean = false) {
    //     if (this.d.cform.saving) {
    //         // return;
    //     }
    //     this.d.cform.saving = true;
    //     const title = this.d.cform.title;
    //     Axios.post("/categories", {
    //         sub,
    //         title
    //     }).then(res => {
    //         if (res && res.status === 422) {
    //             this.error();
    //             this.d.cform.error = res.data.errors;
    //             this.d.cform.saving = false;
    //             return;
    //         }

    //         if (!res || !res.data) {
    //             this.error();
    //             this.d.cform.saving = false;
    //             return;
    //         }

    //         this.d.cform.saving = false;
    //         this.success();
    //         this.d.cform.title = "";
    //         location.reload();
    //     });
    // }

    public editCat(action: string, val: string) {
        this.d.editingCat = true;
        this.d.action = action;
        const inp = document.querySelector("#ctitleInp") as HTMLInputElement;
        inp.value = val;
    }

    public editSub(action: string, val: string, catSlug: string) {
        this.d.editingSub = true;
        this.d.actionSub = action;
        const inp = document.querySelector("#subtitleInp") as HTMLInputElement;
        const select = document.querySelector(
            "#selectCat"
        ) as HTMLSelectElement;
        inp.value = val;
        select.value = catSlug;
    }

    public previewImg(ev) {
        const inp: HTMLInputElement = ev.target;
        this.d.prev = "";
        if (!inp.files) {
            this.d.prev = "";
            return;
        }

        if (inp.files[0]) {
            const reader = new FileReader();

            reader.onload = e => {
                this.d.prev = (e.target as any).result;
            };
            reader.readAsDataURL(inp.files[0]);
        }
    }

    public previewImgArr(ev) {
        const inp: HTMLInputElement = ev.target;
        this.d.prevArr = [];
        if (!inp.files) {
            this.d.prevArr = [];
            return;
        }

        for (const f in inp.files) {
            if (typeof inp.files[f] === 'object') {
                // console.log(typeof f, typeof inp.files[f]);
                const reader = new FileReader();

                reader.onload = e => {
                    this.d.prevArr.push((e.target as any).result);
                };
                reader.readAsDataURL(inp.files[f]);
            }
        }
    }

    public editTag(action: string, val: string) {
        this.d.editingTag = true;
        this.d.actionTag = action;
        const inp = document.querySelector("#titleInp") as HTMLInputElement;
        inp.value = val;
    }

    beforeMount() {
        this.attachToGlobal(this, [
            "openItemsModal",
            "updateRole",
            "completeOrder",
            // "saveCat",
            "editCat",
            "editSub",
            "previewImg",
            "editTag",
            "previewImgArr"
        ]);
    }
}
