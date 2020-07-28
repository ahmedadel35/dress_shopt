(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[7],{

/***/ "./resources/js/pages/product-show.ts":
/*!********************************************!*\
  !*** ./resources/js/pages/product-show.ts ***!
  \********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.js");
/* harmony import */ var vue_property_decorator__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-property-decorator */ "./node_modules/vue-property-decorator/lib/vue-property-decorator.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _mixins_product_mixin__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../mixins/product-mixin */ "./resources/js/mixins/product-mixin.ts");
/* harmony import */ var _mixins_product_show_mixin__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../mixins/product-show-mixin */ "./resources/js/mixins/product-show-mixin.ts");





var ProductShow = /** @class */ (function (_super) {
    Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__extends"])(ProductShow, _super);
    function ProductShow() {
        var _this = _super !== null && _super.apply(this, arguments) || this;
        _this.d = {
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
            mp: _mixins_product_mixin__WEBPACK_IMPORTED_MODULE_3__["EMPTY_PRODUCT"],
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
        _this.ratesRemain = 7;
        return _this;
    }
    ProductShow.prototype.loadProductData = function () {
        var _this = this;
        axios__WEBPACK_IMPORTED_MODULE_2___default.a.get("/product/" + this.slug, {
            params: { avg: "", pi: "" }
        }).then(function (res) {
            if (!res || !res.data) {
                _this.error();
                return;
            }
            res.data.images = res.data.prod.images.map(function (x) {
                return {
                    id: "aww" + Math.round(Math.random() * 1889797),
                    src: x
                };
            });
            _this.d.product = res.data.prod;
            _this.d.product.rate_avg = res.data.rate_avg;
            // console.log(this.d.product);
            _this.loadRates();
        });
    };
    ProductShow.prototype.addToCart = function (item, productInx) {
        var _this = this;
        if (item === void 0) { item = "pItem"; }
        if (productInx === void 0) { productInx = "product"; }
        if (this.d.cartLoader || this.d.addingToCart) {
            return;
        }
        this.d.addingToCart = true;
        // console.log(this.d[item], item);
        var res = this.addToCartNative(this.d[productInx], this.d[item].qty, this.d[item].size, this.d[item].color);
        res.then(function (r) {
            if (r && (r.data.item || r.data.updated)) {
                _this.d.addingToCart = false;
            }
        }).finally(function () { return (_this.d.addingToCart = false); });
    };
    ProductShow.prototype.addToWish = function (itemInx, productInx) {
        var _this = this;
        if (itemInx === void 0) { itemInx = "pItem"; }
        if (productInx === void 0) { productInx = "product"; }
        if (this.d.wishLoader) {
            return;
        }
        this.addToWishList(this.d, productInx, itemInx).then(function (res) {
            if (res && res.item) {
                _this.pushToCartList({ item: res.item });
            }
        });
    };
    ProductShow.prototype.loadRates = function (path, append) {
        var _this = this;
        if (path === void 0) { path = "/product/" + this.pid + "/rates"; }
        if (append === void 0) { append = false; }
        this.d.loadingRates = true;
        this.fillRevDataLoader();
        axios__WEBPACK_IMPORTED_MODULE_2___default.a.get(path).then(function (res) {
            var _a;
            if (!res || !res.data) {
                _this.error();
                return;
            }
            _this.d.revDataLoader = [];
            if (!append) {
                if (!res.data.data.length) {
                    _this.d.emptyRates = true;
                    _this.d.loadingRates = false;
                    return;
                }
                _this.d.revData = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__spread"])(res.data.data);
                // check if user have abilty to delete any rates
                res.data.data.map(function (x) {
                    if (x.user_id !== _this.userId) {
                        _this.d.userCanUpdate = x.can_update;
                        return;
                    }
                });
                // console.log(this.d.userCanUpdate);
            }
            else {
                _this.d.revData = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__spread"])(_this.d.revData.concat(res.data.data));
                // console.log(this.d.revData);
            }
            _this.setUserRev(res.data.data);
            _this.d.nextRevUrl = ((_a = res.data) === null || _a === void 0 ? void 0 : _a.next_page_url) || "";
            // @ts-ignore
            var remain = (res.total - res.to) / 7;
            _this.ratesRemain = remain <= 7 ? remain : 7;
            _this.d.loadingRates = false;
        });
    };
    ProductShow.prototype.addRev = function () {
        var _this = this;
        this.d.savingRev = true;
        var method = "post", path = "/rates";
        if (this.d.userRev.alreadyReved) {
            method = "patch";
            path = "/rates/" + this.d.userRev.id;
        }
        var r = {
            user_id: this.userId,
            product_id: this.pid,
            rate: this.d.userRev.rate,
            message: this.d.userRev.message
        };
        if (!this.d.userRev.message.length) {
            delete r.message;
        }
        axios__WEBPACK_IMPORTED_MODULE_2___default.a[method](path, r).then(function (res) {
            if (!res ||
                !res.data ||
                (method === "post" && !res.data.rate) ||
                (method === "patch" && !res.data.updated)) {
                _this.d.savingRev = false;
                _this.error();
                return;
            }
            if (!_this.d.userRev.alreadyReved) {
                _this.d.revData.unshift(res.data);
                _this.d.userRev.id = parseInt(res.data.id);
                _this.d.userRev.userId = _this.userId;
            }
            else {
                var indx = _this.d.revData.findIndex(function (x) { return x.user_id === _this.userId; });
                _this.d.revData[indx].rate = _this.d.userRev.rate;
                _this.d.revData[indx].message = _this.d.userRev.message;
                _this.d.revData[indx].updated = res.data.updated;
            }
            _this.d.userRev.alreadyReved = true;
            _this.success(_this.getLang(2), _this.getLang(5));
            _this.d.savingRev = false;
        });
    };
    ProductShow.prototype.loadMoreRevs = function () {
        this.loadRates(this.d.nextRevUrl, true);
    };
    ProductShow.prototype.deleteRate = function (id, inx) {
        var _this = this;
        var deleteBtn = this.$root.$refs["deleteRefBtn" + id][0];
        if (deleteBtn && !deleteBtn.classList.contains("d-none")) {
            return;
        }
        deleteBtn.classList.remove("d-none");
        axios__WEBPACK_IMPORTED_MODULE_2___default.a.delete("/rates/" + id).then(function (res) {
            if (!res || res.status !== 204) {
                deleteBtn.classList.add("d-none");
                _this.error();
                return;
            }
            // remove rev from revs array
            _this.d.revData.splice(inx, 1);
            // remove user rev
            _this.d.userRev = {
                userId: 0,
                id: 0,
                index: 0,
                rate: 0,
                message: "",
                alreadyReved: false
            };
            _this.success();
        });
    };
    ProductShow.prototype.loadFeaturedProds = function (path, append) {
        if (path === void 0) { path = "/collection/featured"; }
        if (append === void 0) { append = false; }
        this.loadFeaturedProdsNative(this.d, path, append);
    };
    ProductShow.prototype.openModal = function (slug) {
        // console.log(slug);
        this.openModalNative(this.d, slug, "productShowModal", { avg: true });
    };
    ProductShow.prototype.loadRelatedProds = function (path, append) {
        var _this = this;
        if (path === void 0) { path = "/product/" + this.slug + "/related"; }
        if (append === void 0) { append = false; }
        if (this.d.related.loading || !path.length) {
            return;
        }
        this.d.related.loading = true;
        axios__WEBPACK_IMPORTED_MODULE_2___default.a.get(path).then(function (res) {
            if (!res.data.data) {
                _this.error();
                return;
            }
            res = res.data;
            if (append) {
                _this.d.related.data = _this.d.related.data.concat(Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__spread"])(res.data));
                // setTimeout(_ => this.hideLoader(append), 5000);
            }
            else {
                _this.d.related.data = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__spread"])(res.data);
            }
            // @ts-ignore
            _this.d.related.nextUrl = (res === null || res === void 0 ? void 0 : res.next_page_url) || "";
            // @ts-ignore
            var remain = Math.round((res.total - res.to) / 8);
            _this.d.related.remain = remain <= 8 ? remain : 8;
            _this.d.related.loading = false;
        });
    };
    ProductShow.prototype.setUserRev = function (d) {
        var userId = this.userId;
        var indx = d.findIndex(function (x) { return x.user_id === userId; });
        if (indx > -1) {
            var r = d[indx];
            console.log(r);
            this.d.userRev.index = indx;
            this.d.userRev.userId = userId;
            this.d.userRev.id = Number(r.id);
            this.d.userRev.rate = r.rate;
            this.d.userRev.message = r.message || "";
            this.d.userRev.alreadyReved = true;
            console.log(this.d.userRev);
        }
    };
    ProductShow.prototype.fillRevDataLoader = function () {
        var _this = this;
        Array(this.ratesRemain)
            .fill(1)
            .forEach(function (x) { return _this.d.revDataLoader.push(x); });
    };
    Object.defineProperty(ProductShow.prototype, "slug", {
        get: function () {
            return window["xjs"].slug;
        },
        enumerable: false,
        configurable: true
    });
    Object.defineProperty(ProductShow.prototype, "userId", {
        get: function () {
            return parseInt(window["xjs"].user_id);
        },
        enumerable: false,
        configurable: true
    });
    Object.defineProperty(ProductShow.prototype, "pid", {
        get: function () {
            return parseInt(window["xjs"].pid);
        },
        enumerable: false,
        configurable: true
    });
    ProductShow.prototype.beforeMount = function () {
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
    };
    ProductShow.prototype.mounted = function () {
        var _this = this;
        this.loadProductData();
        this.loadFeaturedProds();
        this.loadRelatedProds();
        this.fillRevDataLoader();
        this.$on("cartDone", function () {
            // check if product exists in cart
            _this.checkIfProductExistsInCartOrWish(_this.d, _this.pid, "pItem");
        });
    };
    ProductShow = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
        vue_property_decorator__WEBPACK_IMPORTED_MODULE_1__["Component"]
    ], ProductShow);
    return ProductShow;
}(Object(vue_property_decorator__WEBPACK_IMPORTED_MODULE_1__["Mixins"])(_mixins_product_mixin__WEBPACK_IMPORTED_MODULE_3__["default"], _mixins_product_show_mixin__WEBPACK_IMPORTED_MODULE_4__["default"])));
/* harmony default export */ __webpack_exports__["default"] = (ProductShow);


/***/ })

}]);
//# sourceMappingURL=7.js.map