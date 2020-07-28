(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[2],{

/***/ "./resources/js/pages/admin-ctrl.ts":
/*!******************************************!*\
  !*** ./resources/js/pages/admin-ctrl.ts ***!
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




var AdminCtrl = /** @class */ (function (_super) {
    Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__extends"])(AdminCtrl, _super);
    function AdminCtrl() {
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
        return _this;
    }
    AdminCtrl.prototype.openItemsModal = function (data, orderId) {
        this.d.items = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__spread"])(JSON.parse(data || "[]"));
        // console.log(JSON.parse(data || "[]"));
        this.d.orderId = orderId;
        var modal = document.getElementById("items-modal");
        if (modal) {
            // @ts-ignore
            new Modal(modal).show();
        }
    };
    AdminCtrl.prototype.updateRole = function (id, role, enc_id) {
        var _this = this;
        var spinner = document.getElementById(id);
        var btn = spinner.parentElement;
        if (!spinner.classList.contains("d-none")) {
            return;
        }
        spinner.classList.remove("d-none");
        btn.classList.add("disabled");
        axios__WEBPACK_IMPORTED_MODULE_3___default.a.patch("/users/" + enc_id + "/role", { role: role }).then(function (res) {
            if (!res || !res.data || !res.data.updated) {
                _this.error();
                spinner.classList.add("d-none");
                btn.classList.remove("disabled");
                return;
            }
            spinner.classList.add("d-none");
            btn.classList.remove("disabled");
            _this.success();
        });
    };
    AdminCtrl.prototype.completeOrder = function (id) {
        var _this = this;
        var spinner = document.getElementById("spinord" + id);
        var btn = spinner.parentElement;
        var icon = document.getElementById("icon" + id);
        if (!spinner.classList.contains("d-none")) {
            return;
        }
        spinner.classList.remove("d-none");
        btn.classList.add("disabled");
        var done = { done: true };
        axios__WEBPACK_IMPORTED_MODULE_3___default.a.patch("/orders/" + id + "/complete", done).then(function (res) {
            if (!res || !res.data || !res.data.updated) {
                _this.error();
                spinner.classList.add("d-none");
                btn.classList.remove("disabled");
                return;
            }
            spinner.classList.add("d-none");
            btn.classList.remove("disabled");
            btn.classList.add("d-none");
            // console.log(btn);
            icon.classList.add("fa-check", "text-success");
            _this.success();
        });
    };
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
    AdminCtrl.prototype.editCat = function (action, val) {
        this.d.editingCat = true;
        this.d.action = action;
        var inp = document.querySelector("#ctitleInp");
        inp.value = val;
    };
    AdminCtrl.prototype.editSub = function (action, val, catSlug) {
        this.d.editingSub = true;
        this.d.actionSub = action;
        var inp = document.querySelector("#subtitleInp");
        var select = document.querySelector("#selectCat");
        inp.value = val;
        select.value = catSlug;
    };
    AdminCtrl.prototype.previewImg = function (ev) {
        var _this = this;
        var inp = ev.target;
        this.d.prev = "";
        if (!inp.files) {
            this.d.prev = "";
            return;
        }
        if (inp.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                _this.d.prev = e.target.result;
            };
            reader.readAsDataURL(inp.files[0]);
        }
    };
    AdminCtrl.prototype.previewImgArr = function (ev) {
        var _this = this;
        var inp = ev.target;
        this.d.prevArr = [];
        if (!inp.files) {
            this.d.prevArr = [];
            return;
        }
        for (var f in inp.files) {
            if (typeof inp.files[f] === 'object') {
                // console.log(typeof f, typeof inp.files[f]);
                var reader = new FileReader();
                reader.onload = function (e) {
                    _this.d.prevArr.push(e.target.result);
                };
                reader.readAsDataURL(inp.files[f]);
            }
        }
    };
    AdminCtrl.prototype.editTag = function (action, val) {
        this.d.editingTag = true;
        this.d.actionTag = action;
        var inp = document.querySelector("#titleInp");
        inp.value = val;
    };
    AdminCtrl.prototype.beforeMount = function () {
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
    };
    AdminCtrl = Object(tslib__WEBPACK_IMPORTED_MODULE_0__["__decorate"])([
        vue_property_decorator__WEBPACK_IMPORTED_MODULE_1__["Component"]
    ], AdminCtrl);
    return AdminCtrl;
}(_super__WEBPACK_IMPORTED_MODULE_2__["default"]));
/* harmony default export */ __webpack_exports__["default"] = (AdminCtrl);


/***/ })

}]);
//# sourceMappingURL=2.js.map