import { Vue, Component, Prop, Watch } from "vue-property-decorator";
import Super from "../pages/super";
import { OrderCtrlData } from "../pages/order-ctrl";
import { UserCtrlData } from "../pages/user-ctrl";
import AddressInterface from "../interfaces/address";
import Axios from 'axios';

@Component
export default class AddressMixin extends Super {
    public async saveAddressNative(
        self: OrderCtrlData | UserCtrlData,
        update: boolean = false,
        param: {withTrans?: boolean} = {}
    ) {
        self.error = Object.assign({}, this.resetFormObj);

        const method = update ? "patch" : "post";
        let uri = "/address";
        if (update) {
            uri += "/" + self.address.id;
        }
        
        // @ts-ignore
        self.form.withTrans = param.withTrans;

        const res = await Axios[method](uri, self.form);
        // console.log(self.form);
        // console.log(res, res.status);
        if (res && res.status === 422) {
            self.hasErrors = true;
            self.error = res.data.errors;
            // console.log(self.error);
            this.error();
            return null;
        }

        if (!res || !res.data || res.status > 204) {
            this.error();
            return null;
        }

        return res.data;
    }


    public validateData(self: OrderCtrlData | UserCtrlData | any) {
        self.addressChecked = false;

        const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (!re.test(self.form.userMail) && !self.userId) {
            return true;
        }

        if (
            /!\(([0-9]{2}|0{1}((x|[0-9]){2}[0-9]{2}))\)\s*[0-9]{3,4}[- ]*[0-9]{4}/.test(
                self.form.phoneNumber
            )
        ) {
            return true;
        }

        // ckeck for length
        const f = self.form;
        const len =
            f.firstName.length ||
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
    }

    get resetFormObj(): AddressInterface {
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
    }
}
