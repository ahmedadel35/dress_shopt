<template>
    <div class="position-relative" :class="dir" style="max-width: 100%">
        <div
            class="left-btn position-absolute text-primary"
            @click="$refs.slider.$emit('slidePre')"
            v-show="arr.length"
        >
            <!-- <i
                class="fas fa-chevron-circle-left fa-3x"
                v-if="dir === 'ltr'"
            ></i> -->
            <i class="fas fa-chevron-circle-left fa-3x"></i>
        </div>
        <slider ref="slider" :options="opts" @slide="onSlide($event)">
            <s-item
                v-for="p in arr"
                :key="p.slug + Math.random()"
                class="col-6 col-sm-4 col-md-2 px-0 ml-1"
                style="font-size:inherit"
            >
                <product-item
                    :product="p"
                    @modal="$emit('modal', $event)"
                    @error="$parent.showNotifyNative($event)"
                    @exists="$parent.showNotifyNative($event)"
                    @added="$parent.pushToCartList($event)"
                    @removed="$parent.removeFromCart($event)"
                    @show-cart-loader="$parent.showCartLoader()"
                    @hide-cart-loader="$parent.hideCartLoader()"
                    @show-wish-loader="$parent.showWishLoaderNative()"
                    @hide-wish-loader="$parent.hideWishLoaderNative()"
                >
                </product-item>
            </s-item>
            <s-item
                v-for="pol in loadingData"
                :key="Math.random() * pol"
                class="col-6 col-sm-4 col-md-2 px-0 mr-2"
                style="font-size:inherit"
            >
                <product-item-loader cls="w-100"> </product-item-loader>
            </s-item>
        </slider>
        <div
            class="right-btn position-absolute text-primary"
            @click="$refs.slider.$emit('slideNext')"
            v-show="arr.length"
        >
            <!-- <i
                class="fas fa-chevron-circle-right fa-3x"
                v-if="dir === 'ltr'"
            ></i> -->
            <i class="fas fa-chevron-circle-right fa-3x"></i>
        </div>
    </div>
</template>
<script lang="ts">
import { Vue, Component, Prop, Watch, Ref } from "vue-property-decorator";
import { slider } from "vue-concise-slider";
import ProductInterface from "../interfaces/product";
import SliderInterface from "../interfaces/slider";

@Component
export default class ProductSlider extends Vue {
    @Prop({ type: Array, required: true }) public products: ProductInterface[];
    @Prop({ type: Object }) public options: SliderInterface;
    @Prop({ type: Number, default: 0 }) public still: number;

    @Ref() readonly slider: slider;

    public currentPage: number = 0;
    public arr = this.products;
    public opts: SliderInterface = this.options || {
        currentPage: 0,
        infinite: 1,
        slidesToScroll: 1,
        loop: false,
        pagination: false
    };
    public loadingData: number[] = [];
    public remain: number = this.still;

    public isTouchScreen() {
        const prefixes = " -webkit- -moz- -o- -ms- ".split(" ");

        const mq = query => {
            return window.matchMedia(query).matches;
        };

        if (
            "ontouchstart" in window ||
            // @ts-ignore
            (window.DocumentTouch && document instanceof DocumentTouch)
        ) {
            return true;
        }
        const query = [
            "(",
            prefixes.join("touch-enabled),("),
            "heartz",
            ")"
        ].join("");
        return mq(query);
    }

    public changeCountBasedOnScreenSize() {
        // console.log(window.outerWidth);
        if (window.outerWidth === 768) {
            this.setSlidesCount(1);
        } else if (window.outerWidth > 768) {
            // do something for medium screens
            this.setSlidesCount(1);
        }
    }

    public onSlide(s: { currentPage: number }) {
        this.currentPage = s.currentPage;
        if (this.arr.length && s.currentPage >= this.arr.length - 1) {
            this.$emit("load-more", true);
            if (!this.loadingData.length) {
                this.fillLoadingData();
            }
        }
    }

    private setSlidesCount(n: number): void {
        this.opts.slidesToScroll = n;
        // @ts-ignore
        this.opts.infinite = n;
    }

    private fillLoadingData() {
        // console.log(this.remain);
        Array(this.remain)
            .fill(1)
            .forEach(x => this.loadingData.push(x));
    }

    get dir(): string {
        return document.documentElement.dir;
    }

    @Watch("products")
    onProductsChange(val: ProductInterface[]) {
        this.loadingData = [];
        this.arr = val;
    }

    @Watch("still")
    onStillChange(val: number) {
        // console.log(val);
        this.remain = val;
    }

    mounted() {
        // console.log(this.loadingData);
        this.fillLoadingData();
        this.changeCountBasedOnScreenSize();
        // this.opts.pagination = !this.isTouchScreen();
    }
}
</script>
<style lang="scss">
$breakpoints: (
    "sm": 576px,
    "md": 768px,
    "lg": 992px,
    "xl": 1200px
) !default;
@import "../../sass/include-media";
</style>
<style lang="scss" scoped>
.slider-container {
    white-space: unset;
    .slider-item {
        text-align: unset;
        .product-card {
            .bottom-btns {
                top: 70%;
                &:first-child {
                    margin-bottom: 0.5rem;
                }
            }
        }
    }
}
.right-btn,
.left-btn {
    top: 50%;
    transform: translateY(-50%);
    z-index: 99;
    opacity: 0.7;
    &:hover {
        opacity: 1;
        cursor: pointer;
    }
}
.right-btn {
    right: -20px;
}
.left-btn {
    left: -20px;
}
// .rtl {
//     .right-btn {
//         right: 90%;
//         left: -20px;
//     }
//     .left-btn {
//         left: 100%;
//         right: -20px;
//     }
// }
</style>
