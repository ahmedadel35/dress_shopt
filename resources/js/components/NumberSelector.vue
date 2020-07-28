<template>
    <div class="">
        <div class="input-group mb-3">
            <div
                class="input-group-prepend text-primary transition"
                @click="decrement()"
                :class="{ disabled: current <= 1 || disabled }"
            >
                <span class="input-group-text transition">
                    <i class="fas fa-minus"></i>
                </span>
            </div>
            <input
                type="number"
                class="form-control"
                :class="{
                    'is-invalid': current > count || current < 1,
                    'is-valid': current < count && current > 0,
                }"
                placeholder="1"
                min="1"
                :max="count"
                v-model="current"
                @keyup="onKeyPressed()"
                @change="onKeyPressed()"
                :disabled="disabled"
            />
            <div
                class="input-group-append text-primary transition"
                @click="increment()"
                :class="{ disabled: current >= count || disabled }"
            >
                <span class="input-group-text transition">
                    <i class="fas fa-plus"></i>
                </span>
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import { Vue, Component, Prop, Watch } from "vue-property-decorator";

@Component
export default class NumberSelector extends Vue {
    @Prop({ type: Number, required: true }) public count: number;
    @Prop({ type: Number, default: 1 }) public start: number;
    @Prop({type:Boolean, default: false}) public disabled: boolean;

    public current: number = this.start;

    public increment() {
        if(this.disabled) return;
        if (this.current < this.count) {
            this.current++;
        }
        this.emitVal();
    }

    public decrement() {
        if(this.disabled) return;
        if (this.current > 1) {
            this.current--;
        }
        this.emitVal();
    }

    public onKeyPressed() {
        if(this.disabled) return;
        if (this.current > this.count) {
            this.current = this.count;
        } else if (this.current < 1) {
            this.current = 1;
        }

        this.emitVal();
    }

    public emitVal() {
        this.$emit("current-amount", this.current);
    }

    @Watch('start')
    onStartChange(val: number, oldVal: number) {
        this.current = val;
    }

    @Watch('disabled')
    onDisabledChange(val: boolean) {
        this.disabled = val;
    }
}
</script>
<style lang="scss" scoped>
.input-group-text {
    &:hover {
        cursor: pointer;
        color: var(--white);
        background-color: var(--blue);
    }
}
.disabled {
    .input-group-text {
        color: #c1c1c1;
        &:hover {
            color: var(--white);
            cursor: not-allowed;
            background-color: var(--danger);
        }
    }
}
.form-control {
    max-width: 8rem;
}
</style>
