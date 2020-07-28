(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[6],{

/***/ "./resources/js/pages/product-list.ts":
/*!********************************************!*\
  !*** ./resources/js/pages/product-list.ts ***!
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
/* harmony import */ var _mixins_product_list_mixin__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../mixins/product-list-mixin */ "./resources/js/mixins/product-list-mixin.ts");





var ProductList = /** @class */ (function (_super) {
    Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__extends"])(ProductList, _super);
    function ProductList() {
        var _this = _super !== null && _super.apply(this, arguments) || this;
        _this.d = {
            data: [],
            mp: _mixins_product_mixin__WEBPACK_IMPORTED_MODULE_3__["EMPTY_PRODUCT"],
            loadingData: [],
            landList: false,
            sliderOpt: {},
            cat_slug: _this.extractSlug(),
            nextUrl: "",
            remain: 15,
            loadingProducts: true,
            scroll: 0,
            activeSlug: _this.extractSlug(),
            empty: false,
            doc: {
                route: "",
                method: "GET",
                info: "",
                url_with_params: "",
                test_curl: "",
                response: "",
                res_doc: [200, ""],
                headers: [],
                url_params: [],
                query: [],
                parent: ""
            },
            cart: {
                items: [],
                wish: [],
                count: 0,
                total: 0
            },
            item: {
                size: 0,
                color: 0,
                qty: 1,
                wishId: 0,
                wishing: false
            },
            cartLoaded: 0.555,
            cartLoader: true,
            wishLoader: true,
            currentFilter: "",
            currentFilterIndx: "pop",
            colors: [],
            sizes: [],
            prices: {
                min: 0,
                max: 0
            },
            selectedPrices: { min: 0, max: 0 },
            filters: {
                sizes: [],
                colors: [],
                price: {
                    min: 0,
                    max: 0
                },
                stars: 0
            },
            q: "",
            searching: false,
            resetFilters: Math.random(),
            userId: 0,
            scrollTop: 0
        };
        return _this;
    }
    ProductList.prototype.openModal = function (slug) {
        this.openModalNative(this.d, slug, "productModal");
    };
    ProductList.prototype.loadData = function (path, append, isBack, sort) {
        var _this = this;
        if (path === void 0) { path = "/collection/" + this.d.cat_slug; }
        if (append === void 0) { append = false; }
        if (isBack === void 0) { isBack = false; }
        if (sort === void 0) { sort = this.d
            .currentFilterIndx; }
        // console.log(this.d.q);
        if (!append && !this.d.searching) {
            this.d.activeSlug = path.split("/")[2];
        }
        if (this.d.searching) {
            this.d.activeSlug = this.d.q;
        }
        this.d.empty = false;
        this.showLoader(append);
        axios__WEBPACK_IMPORTED_MODULE_2___default.a.get(path, {
            params: { sort: sort, filters: this.d.filters, q: this.d.q }
        }).then(function (res) {
            if (!res || !res.data.data) {
                _this.error();
                return;
            }
            _this.setDoc(_this.d.activeSlug, isBack, append, _this.d.searching);
            if (!res.data.data.length) {
                _this.hideLoader();
                _this.d.empty = true;
                return;
            }
            res = res.data;
            _this.setDoc(_this.d.activeSlug, true, append, _this.d.searching);
            if (append) {
                _this.d.data = _this.d.data.concat(Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__spread"])(res.data));
                // setTimeout(_ => this.hideLoader(append), 5000);
            }
            else {
                if (_this.d.scrollTop > 10) {
                    // window.scrollTo(0, 0);
                    // console.log("scrolling");
                    document.body.scrollTop = 0;
                    document.documentElement.scrollTop = 0;
                }
                _this.d.data = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__spread"])(res.data);
                // this.hideLoader(append);
            }
            _this.doCalc(res.data, append);
            _this.d.nextUrl = res.next_page_url || "";
            _this.d.remain = res.total - res.to;
            _this.d.loadingProducts = false;
            _this.d.searching = false;
            // setTimeout(_ => this.hideLoader(append), 4000);
            _this.hideLoader(append);
        });
    };
    ProductList.prototype.doCalc = function (data, append) {
        var _this = this;
        var prices = [this.d.prices.min, this.d.prices.max];
        if (!append) {
            // this.d.colors = [];
            // prices = [];
        }
        data.map(function (p) {
            _this.addOrRemove("colors", p);
            _this.addOrRemove("sizes", p);
            prices.push(p.saved_price);
        });
        prices = prices.sort(function (a, b) { return a - b; });
        // console.log(prices);
        this.d.prices = {
            min: prices[0],
            max: prices[prices.length - 1]
        };
        this.d.selectedPrices.max = Math.round((this.d.prices.min + this.d.prices.max) / 2 + this.d.prices.min);
    };
    ProductList.prototype.addOrRemove = function (data, p) {
        var e_1, _a;
        var _loop_1 = function (color) {
            if (!this_1.d[data].some(function (b) { return b.txt === color; })) {
                this_1.d[data].push({
                    id: "was" + Math.round(Math.random() * 156133),
                    txt: color,
                    checked: false
                });
            }
        };
        var this_1 = this;
        try {
            for (var _b = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__values"])(p[data]), _c = _b.next(); !_c.done; _c = _b.next()) {
                var color = _c.value;
                _loop_1(color);
            }
        }
        catch (e_1_1) { e_1 = { error: e_1_1 }; }
        finally {
            try {
                if (_c && !_c.done && (_a = _b.return)) _a.call(_b);
            }
            finally { if (e_1) throw e_1.error; }
        }
    };
    ProductList.prototype.getProducts = function (path) {
        if (path === void 0) { path = ""; }
        if (path === "/collection/" + this.d.activeSlug) {
            return;
        }
        this.d.activeSlug = path;
        this.d.searching = false;
        this.d.q = " ";
        this.d.q = "";
        this.resetAllfilters();
        // window.screenTop(0, 0);
        this.loadData(path);
        // console.log(this.d.q.length);
    };
    ProductList.prototype.checkIfReachedBottom = function () {
        var _this = this;
        window.onscroll = function (event) {
            _this.d.scrollTop =
                document.documentElement.clientHeight +
                    document.documentElement.scrollTop;
            // check if user has reached the end of page
            if (_this.d.scrollTop >=
                document.querySelector("#component-container").scrollHeight &&
                _this.d.data.length &&
                "" !== _this.d.nextUrl &&
                !_this.d.loadingProducts) {
                _this.loadData(_this.d.nextUrl, true);
                // console.log(this.d.nextUrl);
            }
        };
    };
    ProductList.prototype.setDoc = function (slug, isBack, append, isSearch) {
        if (isBack === void 0) { isBack = false; }
        if (append === void 0) { append = false; }
        if (isSearch === void 0) { isSearch = false; }
        if (append)
            return;
        var method = isBack ? "replaceState" : "pushState";
        var slugSpace = slug.replace(/-/g, " ");
        var title = "LavaStore - " + slugSpace;
        var url = "/" + this.lang() + "/products/" + slug;
        if (isSearch) {
            url = "/" + this.lang() + "/products/find?q=" + encodeURI(slug);
        }
        window.history[method]({
            slug: slug
        }, title, url);
        document.title = title;
        document.querySelector("#ptitle").textContent = title;
        // this.loadData(slug);
    };
    ProductList.prototype.addToCart = function () {
        // console.log('adding to cart product');
        var res = this.addToCartNative(this.d.mp, this.d.item.qty, this.d.item.size, this.d.item.color);
    };
    ProductList.prototype.removeFromCart = function (id) {
        this.removeFromCartNative(id);
    };
    ProductList.prototype.addToWish = function () {
        var _this = this;
        this.addToWishList(this.d, "mp").then(function (res) {
            if (res && res.item) {
                _this.pushToCartList({ item: res.item });
            }
        });
    };
    ProductList.prototype.filterData = function (sort, str) {
        if (sort === void 0) { sort = "pop"; }
        this.d.currentFilter = str;
        this.d.currentFilterIndx = sort;
        var uri = "/collection/" + this.d.activeSlug;
        if (this.d.q.length) {
            this.d.searching = true;
            uri = "/collection/find?q=" + this.d.q;
        }
        this.loadData(uri, false);
    };
    ProductList.prototype.setSideFilters = function (type, data) {
        var _this = this;
        // console.log(type, data);
        if (type === "size") {
            this.d.filters.sizes = [];
            data.forEach(function (x) { return _this.d.filters.sizes.push(x.txt); });
        }
        else if (type === "color") {
            this.d.filters.colors = [];
            data.forEach(function (x) { return _this.d.filters.colors.push(x.txt); });
        }
        else if (type === "price") {
            var min = this.d.selectedPrices.min;
            var max = this.d.selectedPrices.max;
            this.d.filters.price = {
                min: min <= max ? min : max,
                max: max >= min ? max : min
            };
        }
        else {
            this.d.filters.stars = data;
        }
        // console.log(this.d.filters);
        var uri = "/collection/" + this.d.activeSlug;
        if (this.d.q.length) {
            this.d.searching = true;
            uri = "/collection/find?q=" + this.d.q;
        }
        this.loadData(uri, false);
    };
    ProductList.prototype.searchFor = function () {
        this.d.searching = true;
        this.loadData("/collection/find");
    };
    ProductList.prototype.checkSearchForm = function () {
        var findPage = location.pathname.indexOf("/products/find") > -1;
        if (findPage) {
            this.d.q = location.href.split("q=")[1];
            this.searchFor();
            return;
        }
        this.loadData();
    };
    ProductList.prototype.resetAllfilters = function () {
        this.d.resetFilters = Math.round(Math.random() * 99999);
        this.d.filters = {
            sizes: [],
            colors: [],
            price: {
                min: 0,
                max: 0
            },
            stars: 0
        };
        this.loadData();
    };
    ProductList.prototype.hasFilterActive = function () {
        var f = this.d.filters;
        return (f.sizes.length > 0 ||
            f.colors.length > 0 ||
            f.price.min > 0 ||
            f.price.max > 0 ||
            f.stars > 0);
    };
    ProductList.prototype.showLoader = function (append) {
        if (append === void 0) { append = false; }
        this.d.loadingProducts = true;
        if (!append) {
            this.d.remain = 15;
            this.d.data = [];
        }
        this.fillLoadingData(this.d);
    };
    ProductList.prototype.hideLoader = function (append) {
        if (append === void 0) { append = false; }
        this.d.loadingProducts = false;
        this.d.loadingData = [];
    };
    ProductList.prototype.beforeMount = function () {
        this.attachToGlobal(this, [
            "openModal",
            "getProducts",
            "showNotify",
            "pushToCartList",
            "addToCart",
            "removeFromCart",
            "addToWish",
            "filterData",
            "setSideFilters",
            "resetAllfilters",
            "hasFilterActive"
        ]);
        this.fillLoadingData(this.d);
    };
    ProductList.prototype.mounted = function () {
        var _this = this;
        this.checkIfReachedBottom();
        this.$on("cartDone", function (_) {
            _this.d.cartLoaded = Math.random() * 10000;
        });
        this.checkSearchForm();
        window.onpopstate = function (e) {
            if (e.state) {
                // console.log(e.state);
                _this.loadData("/collection/" + e.state.slug, false, true);
            }
        };
    };
    ProductList = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
        vue_property_decorator__WEBPACK_IMPORTED_MODULE_1__["Component"]
    ], ProductList);
    return ProductList;
}(Object(vue_property_decorator__WEBPACK_IMPORTED_MODULE_1__["Mixins"])(_mixins_product_mixin__WEBPACK_IMPORTED_MODULE_3__["default"], _mixins_product_list_mixin__WEBPACK_IMPORTED_MODULE_4__["default"])));
/* harmony default export */ __webpack_exports__["default"] = (ProductList);


/***/ })

}]);
//# sourceMappingURL=6.js.map