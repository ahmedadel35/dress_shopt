import { Vue, Component, Prop, Watch } from "vue-property-decorator";
import Super from "../pages/super";
import { ProductListData } from '../pages/product-list';
import { HomeData } from '../pages/home';

@Component
export default class ProductListMixin extends Super {
    protected fillLoadingData(self: ProductListData | HomeData) {
        Array(self.remain)
            .fill(1)
            .forEach(x => self.loadingData.push(x));
    }
}