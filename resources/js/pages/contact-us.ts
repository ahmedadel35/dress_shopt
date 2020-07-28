import { Component } from "vue-property-decorator";
import Super from "./super";
import CartInterface from "../interfaces/cart";
import Axios from "axios";

export interface Form {
    userName: string;
    userMail: string;
    userMessage: string;
    sending?: boolean;
    hasErrors?: boolean;
}

export interface ContactUsData {
    cart: CartInterface;
    cartLoader: boolean;
    wishLoader: boolean;
    q: string;
    userId: number;
    scrollTop: number;
    form: Form;
    errors: Form;
}

@Component
export default class ContactUs extends Super {
    public d: ContactUsData = {
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

    public sendMail() {
        this.d.form.hasErrors = this.validateMail();
        if (this.d.form.sending || this.d.form.hasErrors) {
            return;
        }

        this.d.form.sending = true;

        Axios.post("/mail", this.d.form).then(res => {
            if (!res || !res.data) {
                this.error();
                this.d.form.sending = false;
                return;
            }

            if (res.status === 422) {
                this.d.errors = res.data.errors;
                this.d.form.hasErrors = true;
                this.d.form.sending = false;
                return;
            }

            this.success(this.getLang(2), this.getLang(12));
            this.d.form.sending = false;
            this.d.form.userMessage = '';
        });
    }

    public validateOnKeyPress() {
        this.d.form.hasErrors = this.validateMail();
    }

    private validateMail() {
        const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (!re.test(this.d.form.userMail)) {
            return true;
        }

        if (this.d.form.userMessage.length < 5) {
            return true;
        }

        return false;
    }

    beforeMount() {
        this.attachToGlobal(this, ["sendMail", "validateOnKeyPress"]);
    }

    mounted() {
        this.d.form.userName = window["xjs"]?.userName ?? "";
        this.d.form.userMail = window["xjs"]?.userMail ?? "";
    }
}
