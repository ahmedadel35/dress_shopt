(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[4],{

/***/ "./resources/js/pages/cart.ts":
/*!************************************!*\
  !*** ./resources/js/pages/cart.ts ***!
  \************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.js");
/* harmony import */ var vue_property_decorator__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-property-decorator */ "./node_modules/vue-property-decorator/lib/vue-property-decorator.js");
/* harmony import */ var _mixins_product_mixin__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../mixins/product-mixin */ "./resources/js/mixins/product-mixin.ts");



var Cart = /** @class */ (function (_super) {
    Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__extends"])(Cart, _super);
    function Cart() {
        var _this = _super !== null && _super.apply(this, arguments) || this;
        _this.d = {
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
        return _this;
    }
    Cart.prototype.removeItemFromCart = function (c, id) {
        this.showBtnLoader(id);
        this.removeItem("cart", c);
    };
    Cart.prototype.updateCartItem = function (c, id) {
        var _this = this;
        this.showBtnLoader(id);
        this.updateCartNative(c.product, c.qty, c.size, c.color, c).then(function (res) {
            _this.hideBtnLoader(id);
        });
    };
    Cart.prototype.showBtnLoader = function (id) {
        var spinner = document.querySelector("#" + id);
        if (spinner) {
            spinner.classList.remove("d-none");
        }
    };
    Cart.prototype.hideBtnLoader = function (id) {
        var spinner = document.querySelector("#" + id);
        if (spinner) {
            spinner.classList.add("d-none");
        }
    };
    Cart.prototype.beforeMount = function () {
        this.attachToGlobal(this, [
            "removeItemFromCart",
            "updateCartItem",
            "addOrder",
            "updateOrder"
        ]);
    };
    Cart.prototype.mounted = function () {
        var _this = this;
        this.$on("cartDone", function (_) {
            _this.d.loadingData = [];
        });
    };
    Cart = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
        vue_property_decorator__WEBPACK_IMPORTED_MODULE_1__["Component"]
    ], Cart);
    return Cart;
}(Object(vue_property_decorator__WEBPACK_IMPORTED_MODULE_1__["Mixins"])(_mixins_product_mixin__WEBPACK_IMPORTED_MODULE_2__["default"])));
/* harmony default export */ __webpack_exports__["default"] = (Cart);


/***/ })

}]);
//# sourceMappingURL=4.js.map