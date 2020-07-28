(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[3],{

/***/ "./resources/js/pages/admin-product-list.ts":
/*!**************************************************!*\
  !*** ./resources/js/pages/admin-product-list.ts ***!
  \**************************************************/
/*! exports provided: EmptyForm, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "EmptyForm", function() { return EmptyForm; });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.js");
/* harmony import */ var vue_property_decorator__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-property-decorator */ "./node_modules/vue-property-decorator/lib/vue-property-decorator.js");
/* harmony import */ var _mixins_product_mixin__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../mixins/product-mixin */ "./resources/js/mixins/product-mixin.ts");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_3__);





var EmptyForm = {
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
var AdminProductList = /** @class */ (function (_super) {
    Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__extends"])(AdminProductList, _super);
    function AdminProductList() {
        var _this = _super !== null && _super.apply(this, arguments) || this;
        _this.d = {
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
            mp: _mixins_product_mixin__WEBPACK_IMPORTED_MODULE_2__["EMPTY_PRODUCT"],
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
        return _this;
    }
    AdminProductList.prototype.openModal = function (slug) {
        this.openModalNative(this.d, slug, "adminProductModal", {
            avg: true,
            pi: true
        });
    };
    AdminProductList.prototype.delete = function (slug, id) {
        var _this = this;
        var spinner = document.getElementById("spinnerDel" + id);
        var btn = spinner.parentElement;
        var product = document.getElementById("prod" + id);
        if (btn.classList.contains("disabled")) {
            return;
        }
        spinner.classList.remove("d-none");
        btn.classList.add("disabled");
        axios__WEBPACK_IMPORTED_MODULE_3___default.a.delete("/product/" + slug).then(function (res) {
            if (!res || res.status !== 204) {
                _this.error();
                spinner.classList.add("d-none");
                btn.classList.remove("disabled");
            }
            spinner.classList.add("d-none");
            btn.classList.remove("disabled");
            product.classList.add("d-none");
            _this.success();
        });
    };
    AdminProductList.prototype.create = function (id) {
        this.d.editing = false;
        var modal = document.getElementById(id);
        this.d.form = Object.assign({}, EmptyForm);
        this.d.errors = Object.assign({}, EmptyForm);
        this.d.attr.keys = [''];
        this.d.attr.vals = [''];
        this.d.prev = [];
        if (modal) {
            // @ts-ignore
            new Modal(modal).show();
        }
    };
    AdminProductList.prototype.previewImg = function (ev) {
        var _this = this;
        var inp = ev.target;
        this.d.prev = [];
        if (!inp.files) {
            this.d.prev = [];
            return;
        }
        this.d.form.images = inp;
        for (var f in inp.files) {
            if (typeof inp.files[f] === 'object') {
                // console.log(typeof f, typeof inp.files[f]);
                var reader = new FileReader();
                reader.onload = function (e) {
                    _this.d.prev.push(e.target.result);
                };
                reader.readAsDataURL(inp.files[f]);
            }
        }
    };
    AdminProductList.prototype.saveProd = function () {
        var e_1, _a, e_2, _b, e_3, _c;
        var _this = this;
        if (this.d.saving) {
            return;
        }
        this.d.saving = true;
        var form = new FormData();
        form.append("title", this.d.form.title);
        // console.log(form.get('title'));
        form.append("price", this.d.form.price);
        form.append("save", this.d.form.save);
        form.append("info", this.d.form.info);
        form.append("category_slug", this.d.form.category_slug);
        // @ts-ignore
        form.append("more", this.d.form.more);
        // @ts-ignore
        form.append("qty", this.d.form.qty);
        try {
            for (var _d = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__values"])(this.d.form.colors), _e = _d.next(); !_e.done; _e = _d.next()) {
                var c = _e.value;
                // @ts-ignore
                form.append("colors[]", c.text);
            }
        }
        catch (e_1_1) { e_1 = { error: e_1_1 }; }
        finally {
            try {
                if (_e && !_e.done && (_a = _d.return)) _a.call(_d);
            }
            finally { if (e_1) throw e_1.error; }
        }
        try {
            for (var _f = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__values"])(this.d.form.sizes), _g = _f.next(); !_g.done; _g = _f.next()) {
                var s = _g.value;
                // @ts-ignore
                form.append("sizes[]", s.text);
            }
        }
        catch (e_2_1) { e_2 = { error: e_2_1 }; }
        finally {
            try {
                if (_g && !_g.done && (_b = _f.return)) _b.call(_f);
            }
            finally { if (e_2) throw e_2.error; }
        }
        try {
            for (var _h = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__values"])(this.d.form.tags), _j = _h.next(); !_j.done; _j = _h.next()) {
                var t = _j.value;
                // @ts-ignore
                form.append("tags[]", t.slug);
            }
        }
        catch (e_3_1) { e_3 = { error: e_3_1 }; }
        finally {
            try {
                if (_j && !_j.done && (_c = _h.return)) _c.call(_h);
            }
            finally { if (e_3) throw e_3.error; }
        }
        for (var f in this.d.form.images.files) {
            form.append("images[]", this.d.form.images.files[f]);
        }
        // append attrs
        for (var i = 0; i < this.d.attr.keys.length; i++) {
            form.append("keys[]", this.d.attr.keys[i]);
            form.append("vals[]", this.d.attr.vals[i]);
        }
        var path = this.d.editing
            ? "/product/" + this.d.activeSlug
            : "/product";
        // console.log(path, method);
        // console.log(form.get('title'));
        axios__WEBPACK_IMPORTED_MODULE_3___default.a.post(path, form, {
            headers: {
                "Content-Type": "multipart/form-data"
            }
        }).then(function (res) {
            if (res && res.status === 422) {
                _this.d.saving = false;
                _this.error();
                _this.d.errors = res.data.errors;
                // @ts-ignore
                _this.d.form.hasErrors = true;
                return;
            }
            if (!res || !res.data) {
                _this.error();
                _this.d.saving = false;
                return;
            }
            _this.d.saving = false;
            _this.success();
            location.reload();
        });
    };
    AdminProductList.prototype.filteredItems = function () {
        var _this = this;
        return this.d.tags.filter(function (i) {
            // @ts-ignore
            return (
            // @ts-ignore
            i.text.toLowerCase().indexOf(_this.d.form.tag.toLowerCase()) !==
                -1);
        });
    };
    AdminProductList.prototype.starProd = function (id, slug) {
        var _this = this;
        var spinner = document.getElementById("spinnerStar" + id);
        var btn = spinner.parentElement;
        var stared = btn.classList.contains("btn-success");
        var param = stared ? {} : { feat: true };
        if (btn.classList.contains("disabled")) {
            return;
        }
        spinner.classList.remove("d-none");
        btn.classList.add("disabled");
        axios__WEBPACK_IMPORTED_MODULE_3___default.a.patch("/product/star/" + slug, param).then(function (res) {
            if (!res || !res.data || !res.data.updated) {
                _this.error();
                spinner.classList.add("d-none");
                btn.classList.remove("disabled");
                return;
            }
            _this.success();
            spinner.classList.add("d-none");
            btn.classList.remove("disabled");
            if (stared) {
                btn.classList.remove("btn-success");
                btn.classList.add("btn-warning");
            }
            else {
                btn.classList.add("btn-success");
                btn.classList.remove("btn-warning");
            }
        });
    };
    AdminProductList.prototype.edit = function (id, slug) {
        return Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__awaiter"])(this, void 0, void 0, function () {
            var spinner, btn, prod, modal, d, tags, k;
            var _this = this;
            return Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__generator"])(this, function (_a) {
                switch (_a.label) {
                    case 0:
                        spinner = document.getElementById("spinnerEdit" + id);
                        btn = spinner.parentElement;
                        this.d.form = Object.assign({}, EmptyForm);
                        this.d.errors = Object.assign({}, EmptyForm);
                        if (btn.classList.contains("disabled")) {
                            return [2 /*return*/];
                        }
                        this.d.editing = true;
                        spinner.classList.remove("d-none");
                        btn.classList.add("disabled");
                        return [4 /*yield*/, axios__WEBPACK_IMPORTED_MODULE_3___default.a.get("/product/" + slug, {
                                params: { pi: true }
                            })];
                    case 1:
                        prod = _a.sent();
                        if (!prod || !prod.data) {
                            this.error();
                            spinner.classList.add("d-none");
                            btn.classList.remove("disabled");
                            return [2 /*return*/];
                        }
                        modal = document.getElementById("createproduct");
                        d = prod.data;
                        this.d.form.title = d.title;
                        this.d.form.id = d.id;
                        this.d.form.price = d.price;
                        this.d.form.save = d.save;
                        this.d.form.qty = d.qty;
                        // this.d.form.colors = d.colors;
                        d.colors.map(function (c) {
                            // @ts-ignore
                            _this.d.form.colors.push({ text: c });
                        });
                        d.sizes.map(function (s) {
                            // @ts-ignore
                            _this.d.form.sizes.push({ text: s });
                        });
                        this.d.form.info = d.info;
                        this.d.form.category_slug = d.category_slug;
                        this.d.form.images = d.images;
                        this.d.activeSlug = prod.data.slug;
                        this.d.prev = prod.data.images;
                        tags = prod.data.tags;
                        this.d.form.tags = [];
                        tags.forEach(function (x) {
                            var i = { text: x.title, slug: x.slug };
                            // @ts-ignore
                            _this.d.form.tags.push(i);
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
                            for (k in prod.data.pi.more) {
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
                        return [2 /*return*/];
                }
            });
        });
    };
    AdminProductList.prototype.beforeMount = function () {
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
    };
    AdminProductList.prototype.mounted = function () {
        var _this = this;
        this.d.cats = JSON.parse(window["xjs"].cats || "[]");
        // save tags
        var tags = JSON.parse(window["xjs"].tags || "[]");
        tags.forEach(function (x) {
            var i = { text: x.title, slug: x.slug };
            // @ts-ignore
            _this.d.tags.push(i);
        });
        // console.log(this.d.tags);
    };
    AdminProductList = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
        vue_property_decorator__WEBPACK_IMPORTED_MODULE_1__["Component"]
    ], AdminProductList);
    return AdminProductList;
}(Object(vue_property_decorator__WEBPACK_IMPORTED_MODULE_1__["Mixins"])(_mixins_product_mixin__WEBPACK_IMPORTED_MODULE_2__["default"])));
/* harmony default export */ __webpack_exports__["default"] = (AdminProductList);


/***/ })

}]);
//# sourceMappingURL=3.js.map