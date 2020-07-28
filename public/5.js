(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[5],{

/***/ "./resources/js/pages/contact-us.ts":
/*!******************************************!*\
  !*** ./resources/js/pages/contact-us.ts ***!
  \******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var tslib__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! tslib */ "./node_modules/tslib/tslib.es6.js");
/* harmony import */ var vue_property_decorator__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-property-decorator */ "./node_modules/vue-property-decorator/lib/vue-property-decorator.js");
/* harmony import */ var _super__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./super */ "./resources/js/pages/super.ts");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_3__);




var ContactUs = /** @class */ (function (_super) {
    Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__extends"])(ContactUs, _super);
    function ContactUs() {
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
            form: {
                userMail: "",
                userName: "",
                userMessage: "",
                sending: false,
                hasErrors: false
            },
            errors: {
                userMail: "",
                userName: "",
                userMessage: ""
            }
        };
        return _this;
    }
    ContactUs.prototype.sendMail = function () {
        var _this = this;
        this.d.form.hasErrors = this.validateMail();
        if (this.d.form.sending || this.d.form.hasErrors) {
            return;
        }
        this.d.form.sending = true;
        axios__WEBPACK_IMPORTED_MODULE_3___default.a.post("/mail", this.d.form).then(function (res) {
            if (!res || !res.data) {
                _this.error();
                _this.d.form.sending = false;
                return;
            }
            if (res.status === 422) {
                _this.d.errors = res.data.errors;
                _this.d.form.hasErrors = true;
                _this.d.form.sending = false;
                return;
            }
            _this.success(_this.getLang(2), _this.getLang(12));
            _this.d.form.sending = false;
            _this.d.form.userMessage = '';
        });
    };
    ContactUs.prototype.validateOnKeyPress = function () {
        this.d.form.hasErrors = this.validateMail();
    };
    ContactUs.prototype.validateMail = function () {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (!re.test(this.d.form.userMail)) {
            return true;
        }
        if (this.d.form.userMessage.length < 5) {
            return true;
        }
        return false;
    };
    ContactUs.prototype.beforeMount = function () {
        this.attachToGlobal(this, ["sendMail", "validateOnKeyPress"]);
    };
    ContactUs.prototype.mounted = function () {
        var _a, _b, _c, _d;
        this.d.form.userName = (_b = (_a = window["xjs"]) === null || _a === void 0 ? void 0 : _a.userName) !== null && _b !== void 0 ? _b : "";
        this.d.form.userMail = (_d = (_c = window["xjs"]) === null || _c === void 0 ? void 0 : _c.userMail) !== null && _d !== void 0 ? _d : "";
    };
    ContactUs = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
        vue_property_decorator__WEBPACK_IMPORTED_MODULE_1__["Component"]
    ], ContactUs);
    return ContactUs;
}(_super__WEBPACK_IMPORTED_MODULE_2__["default"]));
/* harmony default export */ __webpack_exports__["default"] = (ContactUs);


/***/ })

}]);
//# sourceMappingURL=5.js.map