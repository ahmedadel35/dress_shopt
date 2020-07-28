<template>
    <div>
        <span
            class="performance-rating"
            :class="[{ point: run }, cls]"
            @mousemove="hover"
            @mouseout="mouseLeaved"
            @click="set"
        >
            <i class="fa star-unfilled star">
                &#xf005;&#xf005;&#xf005;&#xf005;&#xf005;
                <i
                    :style="{ width: w + '%' }"
                    class="fa star-filled star star-visible"
                    :class="dir"
                    >&#xf005;&#xf005;&#xf005;&#xf005;&#xf005;</i
                >
            </i>
        </span>
        <a
            :href="'/' + lang + '/product/' + productSlug + '#rates'"
            class="mx-1"
            :class="'text-' + countClass"
            v-if="count"
        >
            ({{ count }})
        </a>
    </div>
</template>
<style lang="scss">
.performance-rating.point:hover {
    cursor: pointer;
}
.star {
    position: relative;
    display: inline-block;
    font-size: 16px;
    letter-spacing: 1px;
    white-space: nowrap;
    &.star-unfilled {
        color: #bbb;
    }
    &.star-filled {
        color: #ffd54f;
        overflow: hidden;
        position: absolute;
        top: 0;
        display: inline-block;
        &.rtl {
            right: 0;
        }
        &.ltr {
            left: 0;
        }
    }
}
</style>

<script lang="ts">
import { Vue, Prop, Component, Watch } from "vue-property-decorator";
@Component
export default class StarRate extends Vue {
    @Prop({ type: Number, required: true }) public percent: number;
    @Prop({ type: Number, required: false }) public count: number;
    @Prop({ type: Boolean }) public run: boolean;
    @Prop({ type: String, default: "muted" }) public countClass: string;
    @Prop({ type: String, default: "" }) public productSlug: string;
    public w: number = 0;
    public current: number = 0;
    public dir = "ltr";
    public lang: string = "en";
    public cls = "";

    public hover(ev): void {
        if (!this.$props.run) return;
        this.w = this.extractX(ev);
    }

    public mouseLeaved(): void {
        if (!this.$props.run) return;
        this.w = this.current;
    }

    public set(ev): void {
        if (!this.$props.run) return;
        this.w = this.extractX(ev);
        this.current = this.w;
        this.$emit("rated", this.getVal());
    }

    public setClass(cls: string) {
        this.cls = cls;
    }

    private getVal() {
        return parseFloat(((this.current / 100) * 5).toFixed(1));
    }

    private extractX(event): number {
        var rect = event.target.getBoundingClientRect();
        const isRtl = document.documentElement.lang === "ar";
        var x = isRtl ? rect.right - event.clientX : event.clientX - rect.left;
        return x;
    }

    @Watch("percent")
    onPercentChanged(val: number, oldVal: number) {
        this.w = (this.percent / 5) * 100;
    }

    @Watch("run")
    onRunChanged(val: boolean, oldVal: boolean) {
        this.run = val;
    }

    mounted() {
        this.w = (this.percent / 5) * 100;
        this.dir = document.dir;
        this.lang = document.documentElement.lang;
    }
}
</script>
