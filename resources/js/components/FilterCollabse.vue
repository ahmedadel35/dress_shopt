<template>
    <div class="card mb-3" :class="cls">
        <div
            class="card-header"
            :class="[headerCls, { active: is_open }]"
            @click.prevent="collapse()"
        >
            <h5>{{ title }}</h5>
            <span class="position-absolute p-2 text-light openBtn">
                <i class="fas fa-minus" v-if="is_open"></i>
                <i class="fas fa-plus" v-else></i>
            </span>
        </div>
        <div
            class="card-body collapsed"
            :class="{ show: is_open }"
            ref="menu"
            :id="id"
        >
            <div class="px-1">
                <slot></slot>
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import { Vue, Component, Prop, Watch } from "vue-property-decorator";

@Component
export default class FilterCollabse extends Vue {
    @Prop({
        type: String,
        default: "collabse" + Math.round(Math.random() * 100000)
    })
    public id: string;
    @Prop({ type: String, default: "" }) public cls: string;
    @Prop({ type: String, default: "" }) public headerCls: string;
    @Prop({ type: String, required: true }) public title: string;
    @Prop({ type: Boolean, default: false }) public expand: boolean;

    public is_open: boolean = this.expand;
    public menu: HTMLDivElement = this.$refs.menu as HTMLDivElement;

    public open() {
        this.menu.style.maxHeight = this.menu.scrollHeight + "px";
        this.is_open = true;
    }

    public close() {
        this.menu.style.maxHeight = "0";
        this.is_open = false;
    }

    public collapse() {
        this.is_open ? this.close() : this.open();
    }

    mounted() {
        this.menu = this.$refs.menu as HTMLDivElement;
        if (this.expand) {
            this.open();
        }
    }
}
</script>
<style lang="scss">
.openBtn {
    top: 5px;
}
.ltr .openBtn {
    right: 15px;
}
.rtl .openBtn {
    left: 15px;
}
</style>
<style lang="scss" scoped>
.collapsed {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.2s ease-out;
    visibility: hidden;
    &.show {
        visibility: visible;
    }
}
.card-header {
    &:hover {
        cursor: pointer;
    }
    &.active {
        background-color: #16568b !important;
    }
}
</style>
