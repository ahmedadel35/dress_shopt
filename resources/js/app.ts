import { Vue } from "vue-property-decorator";
import Axios from "axios";
import Home from "./pages/home";
import SideBar from "./components/sidebar.vue";
// import ProductList from "./pages/product-list";
// const ProductList = () => import();
import StarRate from "./components/StarRate.vue";
import ProductItem from "./components/ProductItem.vue";
import ProductItemLoader from "./components/ProductItemLoader.vue";
import ImgSlider from "./components/ImgSlider.vue";
import { slider, slideritem } from "vue-concise-slider";
import { ContentLoader } from "vue-content-loader";
import TextSelector from "./components/TextSelector.vue";
import NumberSelector from "./components/NumberSelector.vue";
import QuickViewLoader from "./components/QuickViewLoader.vue";
import Notifications from "vue-notification";
import FilterCollabse from "./components/FilterCollabse.vue";
// import ProductShow from "./pages/product-show";
import ProductSlider from "./components/product-slider";
// import Cart from './pages/cart';
// import OrderCtrl from './pages/order-ctrl';
// import ContactUs from './pages/contact-us';
// import UserCtrl from './pages/user-ctrl';
// import AdminCtrl from './pages/admin-ctrl';
// import AdminProductList from './pages/admin-product-list';
// import VueTagsInput from '@johmun/vue-tags-input';

Axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
Axios.interceptors.response.use(
    function(response) {
        // TODO show loader
        console.log(response.data);
        return response;
    },
    function(error) {
        // TODO hide loader
        if (error.response.status === 422) {
            return error.response;
        }
        // console.log(error.response);
        console.log(error);
        return Promise.reject(error);
    }
);

Vue.config.productionTip = false;

Vue.use(Notifications);

Vue.component("sidebar", SideBar);
Vue.component("star-rate", StarRate);
Vue.component("product-item", ProductItem);
Vue.component("product-item-loader", ProductItemLoader);
Vue.component("img-slider", ImgSlider);
Vue.component("slider", slider);
Vue.component("s-item", slideritem);
Vue.component("content-loader", ContentLoader);
Vue.component("text-select", TextSelector);
Vue.component("number-select", NumberSelector);
Vue.component("quickview-loader", QuickViewLoader);
Vue.component("filter-collabse", FilterCollabse);
Vue.component("product-slider", ProductSlider);
Vue.component('vue-tags-input', function (res) {
    // @ts-ignore
    require(["@johmun/vue-tags-input"], res)
});

const app = new Vue({
    el: "#app",
    components: {
        Home,
        'product-list': (res) => {
            // @ts-ignore
            require(["./pages/product-list"], res)
        },
        ProductShow: (res) => {
            // @ts-ignore
            require(["./pages/product-show"], res)
        },
        Cart: (res) => {
            // @ts-ignore
            require(["./pages/cart"], res)
        },
        OrderCtrl: (res) => {
            // @ts-ignore
            require(["./pages/order-ctrl"], res)
        },
        ContactUs: (res) => {
            // @ts-ignore
            require(["./pages/contact-us"], res)
        },
        UserCtrl: (res) => {
            // @ts-ignore
            require(["./pages/user-ctrl"], res)
        },
        AdminCtrl: (res) => {
            // @ts-ignore
            require(["./pages/admin-ctrl"], res)
        },
        AdminProductList: (res) => {
            // @ts-ignore
            require(["./pages/admin-product-list"], res)
        }
    },
    mounted() {
        Axios.interceptors.response.use(
            response => {
                
                return response;
            },
            error => {
                
                this.$refs.childCmp.error();
                return error.response;
            }
        );
        const splash = document.querySelector('#splashScreen') as HTMLDivElement;
        if (splash) {
            splash.classList.add('d-none');
        }
    }
});

console.log('something not happing, testing testing testing...');