<template>
    <div class="row">
        <div class="col-3" v-if="id.length">
            <strong class="text-capitalize">
                {{ title }}
            </strong>
        </div>
        <div class="col-9" :class="{ 'col-12': !id.length }">
            <div
                v-for="(t, tinx) in arr"
                :key="t.id"
                class="px-2 py-1 ml-2 mb-2 border textSelector d-inline-block"
                :class="{
                    'active border-primary text-primary':
                        current === tinx || indxes.some(x => x.inx === tinx),
                    disabled
                }"
                @click="setCurrent(tinx)"
            >
                <span
                    v-if="isColor"
                    class="mr-1 rounded-circle align-middle dot"
                    :style="{
                        'background-color': t.txt + ' !important'
                    }"
                ></span>
                <strong>{{ t.txt }}</strong>
            </div>
            <div
                v-for="q in loaderArr"
                :key="Math.random() * q"
                class="d-inline-block ml-2 mb-2"
                style="width: 50px; height: 30px;"
            >
                <content-loader
                    width="50"
                    height="30"
                    primary-color="#cbcbcd"
                    secondary-color="#02103b"
                >
                    <rect
                        x="0"
                        y="0"
                        rx="0"
                        ry="0"
                        width="50"
                        height="30"
                    ></rect>
                </content-loader>
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import { Vue, Component, Prop, Watch } from "vue-property-decorator";

export interface ArrayInterface {
    id: number;
    txt: string;
    checked?: boolean;
}

@Component
export default class TextSelector extends Vue {
    @Prop({ type: String, required: true }) public id: string;
    @Prop({ type: Array, required: true }) public array:
        | ArrayInterface[]
        | string[];
    @Prop({ type: Boolean, default: false }) public isColor: boolean;
    @Prop({ type: Number, default: 0 }) public start: number;
    @Prop({ type: Boolean, default: false }) public disabled: boolean;
    @Prop({ type: Boolean, default: false }) public multi: boolean;
    @Prop({ type: String, default: "" }) public emitId: string;
    @Prop({ type: Number }) public reset: number;
    @Prop({type: String, default: ''}) public title: string;

    public arr: ArrayInterface[] = this.array as ArrayInterface[];
    public loaderArr: number[] = [1, 2, 3];
    public current: number = this.start;
    public indxes: { inx: number; txt: string }[] = [];

    public addId(old: string[]): ArrayInterface[] {
        return old.map(x => {
            return {
                id: Math.random(),
                txt: x
            };
        });
    }

    public setCurrent(inx: number) {
        if (this.disabled) return;
        if (this.multi) {
            this.current = 0.25;
            console.log(this.indxes);
            const obj = { inx, txt: this.arr[inx].txt };
            const findInx = this.indxes.findIndex(x => x.inx === inx);
            if (findInx > -1) {
                this.indxes.splice(findInx, 1);
            } else {
                this.indxes.push(obj);
            }
            this.$emit(`${this.emitId}-done`, this.indxes);
            return;
        }
        this.current = inx;
        this.$emit(`${this.id}-done`, inx);
    }

    @Watch("array")
    onArrayChange(val: ArrayInterface[] | string[], oldVal: string[]) {
        this.current = this.multi ? 0.25 : this.start;
        if (!val.length) {
            this.loaderArr = [1, 2, 3];
        } else {
            this.loaderArr = [];
        }

        this.arr = this.id.length
            ? this.addId(val as string[])
            : (val as ArrayInterface[]);
    }

    @Watch("start")
    onStartChange(val: number) {
        this.current = val;
    }

    @Watch("disabled")
    onDisabledChange(val: boolean) {
        this.disabled = val;
    }

    @Watch("reset")
    onResetChange(val: number) {
        this.current = this.multi ? 0.25 : this.start;
        this.indxes = [];
    }
}
</script>
<style lang="scss" scoped>
.textSelector {
    transition: color 0.5s;
    &:hover {
        cursor: pointer;
    }
    &.active {
        border-width: medium !important;
    }
    &.disabled {
        opacity: 0.7;
        &:hover {
            cursor: not-allowed;
        }
    }
}
.dot {
    height: 15px;
    width: 15px;
    background-color: #000;
    display: inline-block;
}
</style>
