import { Vue, Component, Prop, Watch } from "vue-property-decorator";
import Super from "../pages/super";
import { ProductShowData } from "../pages/product-show";
import { HomeData } from "../pages/home";
import Axios from 'axios';

@Component
export default class ProductShowMixin extends Super {
    public loadFeaturedProdsNative(
        self: ProductShowData | HomeData,
        path: string = "/collection/featured",
        append: boolean = false
    ) {
        if (self.feat.loading || !path.length) {
            return;
        }

        self.feat.loading = true;
        Axios.get(path).then(res => {
            if (!res.data.data) {
                this.error();
                return;
            }

            res = res.data;

            if (append) {
                self.feat.data = self.feat.data.concat([...res.data]);
                // setTimeout(_ => this.hideLoader(append), 5000);
            } else {
                self.feat.data = [...res.data];
            }
            // @ts-ignore
            self.feat.nextUrl = res?.next_page_url || "";
            // @ts-ignore
            const remain = Math.round((res.total - res.to) / 8);
            self.feat.remain = remain <= 8 ? remain : 8;
            // TODO calc remain if more than 8 then be 8 or less
            self.feat.loading = false;
        });
    }
}
