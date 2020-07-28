<template>
    <div :class="cls">
        <div
            class="card mb-3 p-0 overflow-hidden"
            :class="{ landLoader: is_land }"
        >
            <content-loader
                v-if="!isTouch && !is_land"
                width="200"
                height="300"
                primaryColor="#cbcbcd"
                secondaryColor="#8d4968"
            >
                <rect x="0" y="0" rx="0" ry="0" width="200" height="216" />
                <rect x="10" y="268" rx="0" ry="0" width="146" height="15" />
                <rect x="10" y="244" rx="0" ry="0" width="145" height="13" />
                <rect x="170" y="509" rx="0" ry="0" width="536" height="58" />
            </content-loader>
            <content-loader
                v-if="isTouch && !is_land"
                width="200"
                height="300"
                primaryColor="#cbcbcd"
                secondaryColor="#8d4968"
            >
                <rect x="1" y="0" rx="0" ry="0" width="200" height="120" />
                <rect
                    :x="dir === 'ltr' ? 18 : 42"
                    y="200"
                    rx="0"
                    ry="0"
                    width="145"
                    height="13"
                />
                <rect
                    :x="dir === 'ltr' ? 18 : 42"
                    y="180"
                    rx="0"
                    ry="0"
                    width="145"
                    height="13"
                />
                <rect x="170" y="509" rx="0" ry="0" width="536" height="58" />
                <rect x="17" y="134" rx="0" ry="0" width="171" height="36" />
                <rect x="182" y="198" rx="0" ry="0" width="8" height="1" />
                <rect x="19" y="225" rx="0" ry="0" width="171" height="24" />
                <rect x="34" y="263" rx="6" ry="6" width="133" height="32" />
            </content-loader>
            <div v-if="is_land">
                <content-loader
                    width="400"
                    height="300"
                    primaryColor="#cbcbcd"
                    secondaryColor="#8d4968"
                    :class="{ reverse: dir === 'rtl' }"
                >
                    <rect x="5" y="56" rx="6" ry="6" width="151" height="170" />
                    <rect
                        x="170"
                        y="509"
                        rx="0"
                        ry="0"
                        width="586"
                        height="58"
                    />
                    <rect x="182" y="198" rx="0" ry="0" width="8" height="1" />
                    <rect
                        x="164"
                        y="27"
                        rx="0"
                        ry="0"
                        width="225"
                        height="32"
                    />
                    <rect
                        x="164"
                        y="74"
                        rx="0"
                        ry="0"
                        width="154"
                        height="14"
                    />
                    <rect
                        x="164"
                        y="94"
                        rx="0"
                        ry="0"
                        width="154"
                        height="16"
                    />
                    <rect
                        x="164"
                        y="122"
                        rx="0"
                        ry="0"
                        width="150"
                        height="27"
                    />
                    <rect
                        x="328"
                        y="124"
                        rx="0"
                        ry="0"
                        width="28"
                        height="22"
                    />
                    <rect
                        x="169"
                        y="157"
                        rx="0"
                        ry="0"
                        width="220"
                        height="89"
                    />
                    <rect
                        x="207"
                        y="258"
                        rx="6"
                        ry="6"
                        width="152"
                        height="37"
                    />
                </content-loader>
            </div>
        </div>
    </div>
</template>
<style lang="scss">
.landLoader {
    @media (min-width: 576px) {
        max-width: 550px;
    }
}
svg.reverse {
    transform: scaleX(-1);
}
</style>

<script lang="ts">
import { Vue, Component, Prop, Watch } from "vue-property-decorator";

@Component
export default class ProductItemLoader extends Vue {
    @Prop({ type: Boolean }) public is_land: boolean;
    @Prop({type: String}) public cls: string;
    // @Prop({ type: Array, required: true }) public data: number[];
    public dir: string = "";
    public isTouch: boolean = this.isTouchScreen();

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

    mounted() {
        this.dir = document.dir;
    }
}
</script>
