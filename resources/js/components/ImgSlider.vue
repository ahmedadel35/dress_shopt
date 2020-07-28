<template>
    <div :class="dir">
        <div class="row" :class="{ 'd-none': loading }">
            <div class="col-3 col-md-3">
                <ul
                    class="list-group list-unstyled list-group-flush overflow-hidden w-100 h-100"
                >
                    <li
                        v-for="(img, ignx) in imgArr"
                        :key="img.id"
                        class="mb-2 overflow-hidden"
                        @click="$refs.slider.$emit('slideTo', ignx)"
                    >
                        <img
                            :src="img.src"
                            class="sliderControlImg tranistion img-thumbnail"
                            :class="{
                                'active border-success': ignx === currentPage
                            }"
                        />
                    </li>
                </ul>
            </div>
            <div class="col-9 col-md-9">
                <slider
                    ref="slider"
                    :options="opt"
                    @slide="s => (this.currentPage = s.currentPage || 0)"
                >
                    <s-item v-for="img in imgArr" :key="img.id">
                        <img :src="img.src" class="w-100 h-100" />
                    </s-item>
                </slider>
                <span
                    class="badge badge-danger p-2 off-badge position-absolute tranistion"
                    v-show="save > 0"
                >
                    <strong> {{ offTxt }} {{ save }} % </strong>
                </span>
            </div>
        </div>
        <div :class="{ 'd-none': !loading }">
            <content-loader
                width="450"
                height="475"
                primaryColor="#cbcbcd"
                secondaryColor="#02103b"
            >
                <rect x="86" y="1" rx="6" ry="6" width="354" height="352" />
                <rect x="4" y="1" rx="6" ry="6" width="73" height="75" />
                <rect x="6" y="88" rx="6" ry="6" width="72" height="75" />
                <rect x="7" y="180" rx="6" ry="6" width="72" height="75" />
            </content-loader>
        </div>
    </div>
</template>
<style lang="scss">
$breakpoints: (
    "sm": 576px,
    "md": 768px,
    "lg": 992px,
    "xl": 1200px
) !default;
@import "../../sass/include-media";
.sliderControlImg {
    transition-duration: 0.5s;
    &:hover {
        cursor: pointer;
        transform: scale(1.5);
    }
    &.active {
        border-width: medium;
    }
}
.off-badge {
    top: 5px;
    // left: 1px;
    border-radius: 0 4px 4px 0;
    z-index: 99;
    opacity: 0.8;
    &:hover {
        opacity: 1;
    }
    @include media("<sm") {
        opacity: 1;
    }
}
.rtl .off-badge {
    border-radius: 4px 0 0 4px;
}
.rtl {
    svg {
        transform: scaleX(-1);
    }
}
</style>
<script lang="ts">
import { Vue, Component, Prop, Watch } from "vue-property-decorator";
import SliderInterface from "../interfaces/slider";

@Component
export default class ImgSlider extends Vue {
    @Prop({ type: Array, required: true }) public images: string[];
    @Prop({ type: Object }) public options: SliderInterface;
    @Prop({ type: Boolean, default: false }) public noLoad: boolean;
    @Prop({ type: Number, default: 0 }) public save: number;

    public slider = this.$refs.slider as Vue;
    public imgArr: string[] = this.images;
    public opt: SliderInterface = {
        effect: "slide",
        currentPage: 0,
        loop: true
    };
    public currentPage = this.opt.currentPage;
    public loading = true;

    get offTxt(): string {
        return window["xjs"].xlang[11];
    }

    get dir(): string {
        return document.dir;
    }

    @Watch("images")
    onImagesChange(val: string[], oldVal: string[]) {
        this.loading = true;

        if (!val.length) return;

        if (this.$refs.slider && !this.noLoad) {
            (this.$refs.slider as Vue).$emit("slideTo", 0);
            this.currentPage = 0;
        }

        this.imgArr = [...val];
        // console.log(this.imgArr, this.images);
        // this.$forceUpdate();
        this.loading = false;
    }

    mounted() {}
}
</script>
