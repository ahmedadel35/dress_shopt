(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[0],{

/***/ "./resources/js/mixins/address-mixin.ts":
/*!**********************************************!*\
  !*** ./resources/js/mixins/address-mixin.ts ***!
  \**********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.js");
/* harmony import */ var vue_property_decorator__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-property-decorator */ "./node_modules/vue-property-decorator/lib/vue-property-decorator.js");
/* harmony import */ var _pages_super__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../pages/super */ "./resources/js/pages/super.ts");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_3__);




var AddressMixin = /** @class */ (function (_super) {
    Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__extends"])(AddressMixin, _super);
    function AddressMixin() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    AddressMixin.prototype.saveAddressNative = function (self, update, param) {
        if (update === void 0) { update = false; }
        if (param === void 0) { param = {}; }
        return Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__awaiter"])(this, void 0, void 0, function () {
            var method, uri, res;
            return Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__generator"])(this, function (_a) {
                switch (_a.label) {
                    case 0:
                        self.error = Object.assign({}, this.resetFormObj);
                        method = update ? "patch" : "post";
                        uri = "/address";
                        if (update) {
                            uri += "/" + self.address.id;
                        }
                        // @ts-ignore
                        self.form.withTrans = param.withTrans;
                        return [4 /*yield*/, axios__WEBPACK_IMPORTED_MODULE_3___default.a[method](uri, self.form)];
                    case 1:
                        res = _a.sent();
                        // console.log(res, res.status);
                        if (res && res.status === 422) {
                            self.hasErrors = true;
                            self.error = res.data.errors;
                            // console.log(self.error);
                            this.error();
                            return [2 /*return*/, null];
                        }
                        if (!res || !res.data) {
                            this.error();
                            return [2 /*return*/, null];
                        }
                        return [2 /*return*/, res.data];
                }
            });
        });
    };
    AddressMixin.prototype.validateData = function (self) {
        self.addressChecked = false;
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (!re.test(self.form.userMail) && !self.userId) {
            return true;
        }
        if (/!\(([0-9]{2}|0{1}((x|[0-9]){2}[0-9]{2}))\)\s*[0-9]{3,4}[- ]*[0-9]{4}/.test(self.form.phoneNumber)) {
            return true;
        }
        // ckeck for length
        var f = self.form;
        var len = f.firstName.length ||
            f.lastName.length ||
            f.address.length ||
            f.country.length ||
            f.city.length ||
            f.postCode.length ||
            f.phoneNumber.length;
        if (!len) {
            return true;
        }
        return false;
    };
    Object.defineProperty(AddressMixin.prototype, "resetFormObj", {
        get: function () {
            return Object.assign({}, {
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
            });
        },
        enumerable: false,
        configurable: true
    });
    AddressMixin = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
        vue_property_decorator__WEBPACK_IMPORTED_MODULE_1__["Component"]
    ], AddressMixin);
    return AddressMixin;
}(_pages_super__WEBPACK_IMPORTED_MODULE_2__["default"]));
/* harmony default export */ __webpack_exports__["default"] = (AddressMixin);


/***/ }),

/***/ "./resources/js/pages/order-ctrl.ts":
/*!******************************************!*\
  !*** ./resources/js/pages/order-ctrl.ts ***!
  \******************************************/
/*! exports provided: FormInpts, EmptyOrder, default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "FormInpts", function() { return FormInpts; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "EmptyOrder", function() { return EmptyOrder; });
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.js");
/* harmony import */ var vue_property_decorator__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-property-decorator */ "./node_modules/vue-property-decorator/lib/vue-property-decorator.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _mixins_address_mixin__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../mixins/address-mixin */ "./resources/js/mixins/address-mixin.ts");




var FormInpts = {
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
var EmptyOrder = {
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
var OrderCtrl = /** @class */ (function (_super) {
    Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__extends"])(OrderCtrl, _super);
    function OrderCtrl() {
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
        return _this;
    }
    OrderCtrl.prototype.addAddress = function () {
        var _this = this;
        this.d.hasErrors = this.validateData(this.d);
        if (this.d.continue || this.d.hasErrors || this.d.order.id) {
            return;
        }
        this.d.continue = true;
        // check if user edited old address then update
        if (this.d.address.id) {
            this.saveAddress(true).then(function (r) {
                _this.success();
            });
            return;
        }
        // check for address validateion
        this.d.form.check = true;
        this.saveAddress().then(function (r) {
            if (r) {
                _this.success();
            }
        });
        // .then(res => {
        //     this.d.continue = true;
        //     this.saveOrder();
        // });
    };
    OrderCtrl.prototype.loadAddresses = function () {
        return Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__awaiter"])(this, void 0, void 0, function () {
            var res;
            return Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__generator"])(this, function (_a) {
                switch (_a.label) {
                    case 0:
                        this.d.loadingAdresses = true;
                        return [4 /*yield*/, axios__WEBPACK_IMPORTED_MODULE_2___default.a.get("/user/" + this.d.userId + "/addresses")];
                    case 1:
                        res = _a.sent();
                        if (!res || !res.data || !res.data.ads) {
                            this.error();
                            this.d.loadingAdresses = false;
                            return [2 /*return*/];
                        }
                        if (!res.data.ads.length) {
                            this.d.emptyAddressList = true;
                            this.d.loadingAdresses = false;
                            return [2 /*return*/];
                        }
                        this.d.addressesLoader = [];
                        this.d.addresses = res.data.ads;
                        this.d.form.userMail = res.data.userMail;
                        this.d.error = Object.assign({}, this.resetFormObj);
                        this.d.loadingAdresses = false;
                        return [2 /*return*/];
                }
            });
        });
    };
    OrderCtrl.prototype.selectAddress = function (adr) {
        // console.log('selected');
        this.d.error = Object.assign({}, this.resetFormObj);
        this.d.form = Object.assign({}, adr);
        this.d.address = adr;
        this.d.addressChecked = true;
    };
    OrderCtrl.prototype.validateOnChange = function () {
        this.d.hasErrors = this.validateData(this.d);
    };
    OrderCtrl.prototype.showAddressForm = function () {
        this.d.form = Object.assign({}, this.resetFormObj);
        this.d.address = Object.assign({}, this.resetFormObj);
        this.d.showForm = true;
    };
    OrderCtrl.prototype.deleteAddress = function () {
        var _this = this;
        if (this.d.deletingAddress || !this.d.address.id) {
            return;
        }
        this.d.deletingAddress = true;
        axios__WEBPACK_IMPORTED_MODULE_2___default.a.delete("/address/" + this.d.address.id).then(function (res) {
            if (!res || res.status !== 204) {
                _this.error(_this.getLang(1), _this.getLang(13));
                _this.d.deletingAddress = false;
                return;
            }
            var inx = _this.d.addresses.findIndex(function (x) { return x.id === _this.d.address.id; });
            _this.d.addresses.splice(inx, 1);
            _this.success();
            _this.d.deletingAddress = false;
        });
    };
    OrderCtrl.prototype.saveAddress = function (update) {
        if (update === void 0) { update = false; }
        return Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__awaiter"])(this, void 0, void 0, function () {
            var res;
            var _this = this;
            return Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__generator"])(this, function (_a) {
                switch (_a.label) {
                    case 0:
                        this.d.addressChecked = false;
                        return [4 /*yield*/, this.saveAddressNative(this.d, update)];
                    case 1:
                        res = _a.sent();
                        if (!res) {
                            this.d.continue = false;
                            this.d.savingOrder = false;
                            return [2 /*return*/];
                        }
                        if (!update) {
                            this.d.address = res;
                            this.d.addresses.push(res);
                        }
                        else {
                            this.d.addresses = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__spread"])(this.d.addresses).map(function (x) {
                                if (x.id === _this.d.address.id) {
                                    var obj = Object.assign({}, _this.d.form);
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
                        return [2 /*return*/, res];
                }
            });
        });
    };
    OrderCtrl.prototype.storeOrder = function () {
        var _this = this;
        if (this.d.savingOrder || !this.d.address.address) {
            return;
        }
        this.d.savingOrder = true;
        if (this.d.paymentMethod === "accept") {
            var items = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__spread"])(this.d.cart.items);
            var address = this.d.form;
            // get iframe uri
            return axios__WEBPACK_IMPORTED_MODULE_2___default.a.post("/order/payment", {
                address: address,
                userMail: this.d.form.userMail,
                total: this.d.cart.total,
                qty: this.d.cart.count,
                paymentMethod: this.d.paymentMethod,
                items: items
            }).then(function (res) {
                if (!res || !res.data || !res.data.uri) {
                    _this.d.savingOrder = false;
                    _this.error();
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
            .then(function (res) {
            _this.saveOrder();
        })
            .catch(function (err) {
            console.log(err.response);
            _this.d.savingOrder = false;
        });
    };
    OrderCtrl.prototype.saveOrder = function () {
        return Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__awaiter"])(this, void 0, void 0, function () {
            var items, res;
            return Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__generator"])(this, function (_a) {
                switch (_a.label) {
                    case 0:
                        items = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__spread"])(this.d.cart.items);
                        return [4 /*yield*/, axios__WEBPACK_IMPORTED_MODULE_2___default.a.post("/order", {
                                address_id: this.d.address.id,
                                userMail: this.d.form.userMail,
                                total: this.d.cart.total,
                                qty: this.d.cart.count,
                                paymentMethod: this.d.paymentMethod,
                                items: items
                            })];
                    case 1:
                        res = _a.sent();
                        if (!res || !res.data || res.status !== 200) {
                            this.d.savingOrder = false;
                            this.error();
                            return [2 /*return*/];
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
                        return [2 /*return*/];
                }
            });
        });
    };
    OrderCtrl.prototype.showTab = function (tabId) {
        var tab = document.getElementById(tabId);
        if (tab) {
            // @ts-ignore
            new Tab(tab, { height: true }).show();
        }
    };
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
    OrderCtrl.prototype.beforeMount = function () {
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
    };
    OrderCtrl.prototype.mounted = function () {
        var _this = this;
        this.$on("cartDone", function () {
            if (_this.d.userId) {
                // user logged in
                _this.loadAddresses();
            }
            else {
                _this.d.showForm = true;
                _this.d.addressesLoader = [];
            }
        });
        this.d.form.country = "eg";
        this.d.form.gov = "ca";
    };
    OrderCtrl = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
        vue_property_decorator__WEBPACK_IMPORTED_MODULE_1__["Component"]
    ], OrderCtrl);
    return OrderCtrl;
}(Object(vue_property_decorator__WEBPACK_IMPORTED_MODULE_1__["Mixins"])(_mixins_address_mixin__WEBPACK_IMPORTED_MODULE_3__["default"])));
/* harmony default export */ __webpack_exports__["default"] = (OrderCtrl);


/***/ })

}]);
//# sourceMappingURL=0.js.map