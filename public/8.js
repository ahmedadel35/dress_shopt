(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[8],{

/***/ "./resources/js/pages/user-ctrl.ts":
/*!*****************************************!*\
  !*** ./resources/js/pages/user-ctrl.ts ***!
  \*****************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.js");
/* harmony import */ var vue_property_decorator__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-property-decorator */ "./node_modules/vue-property-decorator/lib/vue-property-decorator.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _order_ctrl__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./order-ctrl */ "./resources/js/pages/order-ctrl.ts");
/* harmony import */ var _mixins_address_mixin__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../mixins/address-mixin */ "./resources/js/mixins/address-mixin.ts");





var UserCtrl = /** @class */ (function (_super) {
    Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__extends"])(UserCtrl, _super);
    function UserCtrl() {
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
            items: [],
            orderId: "",
            creating: false,
            form: _order_ctrl__WEBPACK_IMPORTED_MODULE_3__["FormInpts"],
            error: _order_ctrl__WEBPACK_IMPORTED_MODULE_3__["FormInpts"],
            address: _order_ctrl__WEBPACK_IMPORTED_MODULE_3__["FormInpts"],
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
        return _this;
    }
    UserCtrl.prototype.openItemsModal = function (data, orderId) {
        this.d.items = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__spread"])(JSON.parse(data || "[]"));
        // console.log(JSON.parse(data || "[]"));
        this.d.orderId = orderId;
        var modal = document.getElementById("items-modal");
        if (modal) {
            // @ts-ignore
            new Modal(modal).show();
        }
    };
    UserCtrl.prototype.createAddress = function (close, update) {
        if (close === void 0) { close = false; }
        if (update === void 0) { update = false; }
        // console.log('asdasdasd');
        var modal = document.getElementById("address-form-modal");
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
    };
    UserCtrl.prototype.saveAddress = function () {
        var _this = this;
        this.d.hasErrors = this.validateData(this.d);
        if (this.d.creating || this.d.hasErrors) {
            return;
        }
        console.log(this.d.address);
        var update = this.d.address.id > 0;
        this.d.creating = true;
        this.saveAddressNative(this.d, update, { withTrans: true }).then(function (res) {
            if (!res ||
                (!update && !res.address) ||
                (update && res.address)) {
                _this.d.creating = false;
                _this.error();
                return;
            }
            if (!update) {
                _this.createAddressElem(res);
            }
            else {
                location.reload();
            }
            _this.createAddress(true);
            _this.d.address = _this.resetFormObj;
            _this.d.creating = false;
            _this.d.form = _this.resetFormObj;
            document.querySelector("#emptyMess").classList.add("d-none");
        });
    };
    UserCtrl.prototype.editAddress = function (data) {
        // this.d.address = this.resetFormObj;
        this.d.address = JSON.parse(data || "{}");
        this.createAddress(false, true);
    };
    UserCtrl.prototype.deleteAddress = function (addId) {
        var _this = this;
        var loader = document.querySelector("#spinnerDelete" + addId);
        if (loader && !loader.classList.contains("d-none")) {
            return;
        }
        this.showLoader("spinnerDelete" + addId);
        axios__WEBPACK_IMPORTED_MODULE_2___default.a.delete("/address/" + addId).then(function (res) {
            if (!res || res.status !== 204) {
                _this.error();
                _this.hideLoader("spinnerDelete" + addId);
                return;
            }
            _this.hideLoader("address" + addId);
            _this.hideLoader("spinnerDelete" + addId);
        });
    };
    UserCtrl.prototype.validateOnChange = function () {
        this.d.hasErrors = this.validateData(this.d);
    };
    UserCtrl.prototype.previewImg = function (ev) {
        var _this = this;
        var inp = ev.target;
        if (!inp.files || !inp.files[0]) {
            this.d.profile.prev = "";
            return;
        }
        this.d.profile.img = inp;
        var reader = new FileReader();
        reader.onload = function (e) {
            _this.d.profile.prev = e.target.result;
        };
        reader.readAsDataURL(inp.files[0]);
    };
    UserCtrl.prototype.uploadImage = function () {
        var _this = this;
        var _a, _b;
        if (this.d.profile.uploading ||
            !((_a = this.d.profile.img) === null || _a === void 0 ? void 0 : _a.files) ||
            !((_b = this.d.profile.img) === null || _b === void 0 ? void 0 : _b.files[0])) {
            // return;
        }
        this.d.profile.uploading = true;
        var form = new FormData();
        form.append("img", this.d.profile.img.files[0]);
        axios__WEBPACK_IMPORTED_MODULE_2___default.a.post("/user/profile/image", form, {
            headers: {
                "Content-Type": "multipart/form-data"
            }
        }).then(function (res) {
            if (!res || !res.data) {
                _this.error();
                _this.d.profile.uploading = false;
                return;
            }
            if (res.status === 422) {
                _this.d.profile.hasErr = true;
                _this.d.profile.errors.img = res.data.errors.img;
                _this.d.profile.uploading = false;
                return;
            }
            _this.d.profile.uploading = false;
            _this.success();
            _this.d.profile.prev = "";
            // @ts-ignore
            _this.d.profile.img = {};
            _this.updateBgImage(".nav-right .x-pimg", res.data.img);
            _this.updateBgImage(".navbar .x-pimg", res.data.img);
            _this.updateBgImage(".sidebar .x-pimg", res.data.img);
        });
    };
    UserCtrl.prototype.deleteRate = function (rid) {
        var _this = this;
        var spinner = document.getElementById("spinner" + rid);
        var rate = document.getElementById("rateid" + rid);
        if (spinner.parentElement.classList.contains("disabled")) {
            // return;
        }
        spinner.parentElement.classList.add("disabled");
        spinner.classList.remove("d-none");
        axios__WEBPACK_IMPORTED_MODULE_2___default.a.delete("/rates/" + rid).then(function (res) {
            if (!res || res.status !== 204) {
                _this.error();
                spinner.parentElement.classList.remove("disabled");
                spinner.classList.add("d-none");
                return;
            }
            spinner.parentElement.classList.remove("disabled");
            spinner.classList.add("d-none");
            _this.success();
            // remove rate element
            rate.parentElement.classList.add("d-none");
        });
    };
    UserCtrl.prototype.updateBgImage = function (selector, imgUrl) {
        var el = document.querySelector(selector);
        if (el) {
            el.style.backgroundImage = "url(/" + imgUrl + ")";
        }
    };
    UserCtrl.prototype.createAddressElem = function (address) {
        var parent = document.createElement("div");
        parent.id = "address" + address.id;
        parent.classList.add("col-md-6", "mb-3");
        var child = document.createElement("address");
        child.innerHTML = "<div class='card card-body'><h5>" + address.firstName + " " + address.lastName + "</h5> (" + (typeof address.dep === "number" ? address.dep : "") + ") " + address.address + " " + address.city + "<br> <strong>" + address.gov + ", " + address.country + "</strong><br>\n        <a class=\"mt-2\" href=\"tel:" + address.phoneNumber + "\">\n    <i class=\"fas fa-phone-alt mx-1\"></i>\n    " + address.phoneNumber + "\n</a></div>";
        parent.append(child);
        var addressList = document.querySelector("#address-list-row");
        // parent.setAttribute('v-on:click', 'h.d.sdwsdsa()');
        addressList.prepend(parent);
        // this.$forceUpdate();
    };
    UserCtrl.prototype.showLoader = function (id) {
        var loader = document.querySelector("#" + id);
        if (loader) {
            loader.classList.remove("d-none");
        }
    };
    UserCtrl.prototype.hideLoader = function (id) {
        var loader = document.querySelector("#" + id);
        if (loader) {
            loader.classList.add("d-none");
        }
    };
    UserCtrl.prototype.completeOrder = function (id) {
        var _this = this;
        var spinner = document.querySelector("#spinord" + id);
        var btn = spinner.parentElement;
        if (!spinner.classList.contains("d-none")) {
            return;
        }
        btn.classList.add("disabled");
        spinner.classList.remove("d-none");
        axios__WEBPACK_IMPORTED_MODULE_2___default.a.post("/track/" + id + "/complete").then(function (res) {
            if (!res || !res.data || !res.data.updated) {
                _this.error();
                btn.classList.remove("disabled");
                spinner.classList.add("d-none");
                return;
            }
            btn.classList.remove("disabled");
            spinner.classList.add("d-none");
            _this.success();
            btn.classList.add("d-none");
        });
    };
    Object.defineProperty(UserCtrl.prototype, "resetFormObj", {
        get: function () {
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
        },
        enumerable: false,
        configurable: true
    });
    UserCtrl.prototype.beforeMount = function () {
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
    };
    UserCtrl = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
        vue_property_decorator__WEBPACK_IMPORTED_MODULE_1__["Component"]
    ], UserCtrl);
    return UserCtrl;
}(Object(vue_property_decorator__WEBPACK_IMPORTED_MODULE_1__["Mixins"])(_mixins_address_mixin__WEBPACK_IMPORTED_MODULE_4__["default"])));
/* harmony default export */ __webpack_exports__["default"] = (UserCtrl);


/***/ })

}]);
//# sourceMappingURL=8.js.map