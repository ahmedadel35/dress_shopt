import { Component, Mixins } from "vue-property-decorator";
import Super from "./super";
import CartInterface from "../interfaces/cart";
import ProductInterface from "../interfaces/product";
import ProductMixin from "../mixins/product-mixin";
import { EMPTY_PRODUCT } from "../mixins/product-mixin";
import Axios from "axios";
import CategoryInterface from "../interfaces/category";
import TagInterface from "../interfaces/tag";

export const EmptyForm: ProductInterface = {
    id: 0,
    user_id: 0,
    title: "",
    slug: "",
    price: 0,
    priceInt: 0,
    save: 0,
    qty: 0,
    // @ts-ignore
    sizes: [],
    // @ts-ignore
    colors: [],
    category_slug: "",
    images: [],
    info: "",
    saved_price: 0,
    img_path: "",
    // @ts-ignore
    hasErrors: false,
    tags: [],
    // @ts-ignore
    color: "",
    // @ts-ignore
    size: "",
    // @ts-ignore
    tag: "",
    // @ts-ignore
    more: ""
};

export interface AdminProductListData {
    cart: CartInterface;
    cartLoader: boolean;
    wishLoader: boolean;
    q: string;
    userId: number;
    scrollTop: number;
    mp: ProductInterface;
    item: {
        size: number;
        color: number;
        qty: number;
        wishId: number;
        wishing: boolean;
    };
    deleting: boolean;
    form: ProductInterface;
    errors: ProductInterface;
    cats: CategoryInterface[];
    prev: string[];
    saving: boolean;
    tags: {
        text: string;
        slug: string;
    }[];
    // filteredItems: {
    //     text: string,
    //     slug: string
    // }[];
    attr: { keys: string[]; vals: string[] };
    editing: boolean;
    activeSlug: string;
}

@Component
export default class AdminProductList extends Mixins(ProductMixin) {
    public d: AdminProductListData = {
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
        mp: EMPTY_PRODUCT,
        item: {
            size: 0,
            color: 0,
            qty: 1,
            wishId: 0,
            wishing: false
        },
        deleting: false,
        form: EmptyForm,
        errors: EmptyForm,
        cats: [],
        prev: [],
        saving: false,
        tags: [],
        // filteredItems: [],
        attr: { keys: [""], vals: [""] },
        editing: false,
        activeSlug: ""
    };

    public openModal(slug: string) {
        this.openModalNative(this.d, slug, "adminProductModal", {
            avg: true,
            pi: true
        });
    }

    public delete(slug: string, id: number) {
        const spinner = document.getElementById(
            `spinnerDel${id}`
        ) as HTMLDivElement;
        const btn = spinner.parentElement as HTMLButtonElement;
        const product = document.getElementById(`prod${id}`) as HTMLDivElement;
        if (btn.classList.contains("disabled")) {
            return;
        }
        spinner.classList.remove("d-none");
        btn.classList.add("disabled");

        Axios.delete(`/product/${slug}`).then(res => {
            if (!res || res.status !== 204) {
                this.error();
                spinner.classList.add("d-none");
                btn.classList.remove("disabled");
            }

            spinner.classList.add("d-none");
            btn.classList.remove("disabled");
            product.classList.add("d-none");
            this.success();
        });
    }

    public create(id: string) {
        this.d.editing = false;
        const modal = document.getElementById(id) as HTMLDivElement;
        this.d.form = Object.assign({}, EmptyForm);
        this.d.errors = Object.assign({}, EmptyForm);
        this.d.attr.keys = [''];
        this.d.attr.vals = [''];
        this.d.prev = [];
        if (modal) {
            // @ts-ignore
            new Modal(modal).show();
        }
    }

    public previewImg(ev) {
        const inp: HTMLInputElement = ev.target;
        this.d.prev = [];
        if (!inp.files) {
            this.d.prev = [];
            return;
        }
        this.d.form.images = inp;

        for (const f in inp.files) {
            if (typeof inp.files[f] === 'object') {
                // console.log(typeof f, typeof inp.files[f]);
                const reader = new FileReader();

                reader.onload = e => {
                    this.d.prev.push((e.target as any).result);
                };
                reader.readAsDataURL(inp.files[f]);
            }
        }
    }

    public saveProd() {
        if (this.d.saving) {
            return;
        }
        this.d.saving = true;
        const form = new FormData();
        form.append("title", this.d.form.title);
        // console.log(form.get('title'));
        form.append("price", this.d.form.price as string);
        form.append("save", this.d.form.save as string);
        form.append("info", this.d.form.info);
        form.append("category_slug", this.d.form.category_slug);
        // @ts-ignore
        form.append("more", this.d.form.more);
        // @ts-ignore
        form.append("qty", this.d.form.qty);

        for (const c of this.d.form.colors) {
            // @ts-ignore
            form.append("colors[]", c.text);
        }

        for (const s of this.d.form.sizes) {
            // @ts-ignore
            form.append("sizes[]", s.text);
        }

        for (const t of this.d.form.tags as TagInterface[]) {
            // @ts-ignore
            form.append("tags[]", t.slug);
        }

        for (const f in this.d.form.images.files) {
            form.append("images[]", this.d.form.images.files[f]);
        }

        // append attrs
        for (let i = 0; i < this.d.attr.keys.length; i++) {
            form.append("keys[]", this.d.attr.keys[i]);
            form.append("vals[]", this.d.attr.vals[i]);
        }

        const path = this.d.editing
            ? `/product/${this.d.activeSlug}`
            : "/product";
        // console.log(path, method);

        // console.log(form.get('title'));

        Axios.post(path, form, {
            headers: {
                "Content-Type": "multipart/form-data"
            }
        }).then(res => {
            if (res && res.status === 422) {
                this.d.saving = false;
                this.error();
                this.d.errors = res.data.errors;
                // @ts-ignore
                this.d.form.hasErrors = true;
                return;
            }

            if (!res || !res.data) {
                this.error();
                this.d.saving = false;
                return;
            }

            this.d.saving = false;
            this.success();
            location.reload();
        });
    }

    public filteredItems() {
        return this.d.tags.filter(i => {
            // @ts-ignore
            return (
                // @ts-ignore
                i.text.toLowerCase().indexOf(this.d.form.tag.toLowerCase()) !==
                -1
            );
        });
    }

    public starProd(id: number, slug: string) {
        const spinner = document.getElementById(
            `spinnerStar${id}`
        ) as HTMLDivElement;
        const btn = spinner.parentElement as HTMLButtonElement;
        const stared = btn.classList.contains("btn-success");
        const param = stared ? {} : { feat: true };
        if (btn.classList.contains("disabled")) {
            return;
        }

        spinner.classList.remove("d-none");
        btn.classList.add("disabled");

        Axios.patch(`/product/star/${slug}`, param).then(res => {
            if (!res || !res.data || !res.data.updated) {
                this.error();
                spinner.classList.add("d-none");
                btn.classList.remove("disabled");
                return;
            }

            this.success();
            spinner.classList.add("d-none");
            btn.classList.remove("disabled");
            if (stared) {
                btn.classList.remove("btn-success");
                btn.classList.add("btn-warning");
            } else {
                btn.classList.add("btn-success");
                btn.classList.remove("btn-warning");
            }
        });
    }

    public async edit(id: string, slug: string) {
        const spinner = document.getElementById(
            `spinnerEdit${id}`
        ) as HTMLDivElement;
        const btn = spinner.parentElement as HTMLButtonElement;
        this.d.form = Object.assign({}, EmptyForm);
        this.d.errors = Object.assign({}, EmptyForm);

        if (btn.classList.contains("disabled")) {
            return;
        }

        this.d.editing = true;

        spinner.classList.remove("d-none");
        btn.classList.add("disabled");

        // get product data
        const prod = await Axios.get(`/product/${slug}`, {
            params: { pi: true }
        });
        if (!prod || !prod.data) {
            this.error();
            spinner.classList.add("d-none");
            btn.classList.remove("disabled");
            return;
        }

        const modal = document.getElementById(
            "createproduct"
        ) as HTMLDivElement;
        // this.d.form = Object.assign({}, prod.data);
        const d: ProductInterface = prod.data;
        this.d.form.title = d.title;
        this.d.form.id = d.id;
        this.d.form.price = d.price;
        this.d.form.save = d.save;
        this.d.form.qty = d.qty;
        // this.d.form.colors = d.colors;
        d.colors.map(c => {
            // @ts-ignore
            this.d.form.colors.push({text: c});
        });
        d.sizes.map(s => {
            // @ts-ignore
            this.d.form.sizes.push({text: s});
        });
        this.d.form.info = d.info;
        this.d.form.category_slug = d.category_slug;
        this.d.form.images = d.images;

        this.d.activeSlug = prod.data.slug;
        this.d.prev = prod.data.images;
        const tags = prod.data.tags;
        this.d.form.tags = [];
        tags.forEach(x => {
            const i = { text: x.title, slug: x.slug };
            // @ts-ignore
            this.d.form.tags.push(i);
        });
        // @ts-ignore
        this.d.form.hasErrors = false;
        // @ts-ignore
        this.d.form.color = "";
        // @ts-ignore
        this.d.form.size = "";
        // @ts-ignore
        this.d.form.tag = "";
        // @ts-ignore
        this.d.form.more = "";

        if (prod.data.pi) {
            this.d.attr.keys = [];
            this.d.attr.vals = [];
            for (const k in prod.data.pi.more) {
                this.d.attr.keys.push(k);
                this.d.attr.vals.push(prod.data.pi.more[k]);
            }
        }

        this.d.errors = Object.assign({}, EmptyForm);
        if (modal) {
            // @ts-ignore
            new Modal(modal).show();
        }
        spinner.classList.add("d-none");
        btn.classList.remove("disabled");
    }

    beforeMount() {
        this.attachToGlobal(this, [
            "openModal",
            "delete",
            "create",
            "previewImg",
            "saveProd",
            "filteredItems",
            "starProd",
            "edit"
        ]);
    }

    mounted() {
        this.d.cats = JSON.parse(window["xjs"].cats || "[]");

        // save tags
        const tags = JSON.parse(window["xjs"].tags || "[]") as TagInterface[];
        tags.forEach(x => {
            const i = { text: x.title, slug: x.slug };
            // @ts-ignore
            this.d.tags.push(i);
        });
        // console.log(this.d.tags);
    }
}
