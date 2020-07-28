<template>
    <nav
        class="sidebar"
        :class="[
            {
                active: opend,
                'side-left': side === 'left',
                'side-right': side === 'right'
            },
            cls
        ]"
    >
        <div class="container-fluid pt-5">
            <!-- <button
                type="button"
                class="btn btn-sm position-absolute closeSideMenu close py-1 px-2"
                @click.prevent.stop="close()"
                aria-label="Close"
            >
                <span aria-hidden="true">
                    <i class="fas fa-times"></i>
                </span>
            </button> -->
            <slot></slot>
        </div>
    </nav>
</template>
<style lang="scss">
@mixin vendor-prefix($name, $value) {
    @each $vendor in ("-webkit-", "-moz-", "-ms-", "-o-", "") {
        #{$vendor}#{$name}: #{$value};
    }
}
$breakpoints: (
    "sm": 576px,
    "md": 768px,
    "lg": 992px,
    "xl": 1200px
) !default;
@import "../../sass/include-media";
// TODO change width pased on device screen width

.ltr {
    .sidebar {
        &.side-left {
            left: 0;
            margin-left: -86vw;
            @include media(">=sm") {
                margin-left: -55vw;
            }
            &.small {
                margin-left: -66vw;
                @include media(">=sm") {
                    margin-left: -45vw;
                }
            }
            &.active {
                margin-left: 0;
            }
            .closeSideMenu {
                left: 100%;
                padding-right: 10px;
                &:hover {
                    @include vendor-prefix("border-radius", "0 50% 50% 0");
                }
            }
        }
        &.side-right {
            right: 0;
            margin-right: -86vw;
            @include media(">=sm") {
                margin-right: -55vw;
            }
            &.small {
                margin-right: -56vw;
                @include media(">=sm") {
                    margin-right: -35vw;
                }
            }
            &.active {
                margin-right: 0;
            }
            .closeSideMenu {
                right: 100%;
                padding-left: 10px;
                &:hover {
                    @include vendor-prefix("border-radius", "50% 0 0 50%");
                }
            }
        }
        @include media("<=md") {
            #sidebar {
                margin-left: -86vw;
            }
            #sidebar.active {
                margin-left: 0;
            }
            #content {
                width: 100%;
            }
            #content.active {
                width: calc(100% - 86vw);
            }
        }
    }
}
.rtl {
    .sidebar {
        &.side-left {
            right: 0;
            margin-right: -86vw;
            @include media(">=sm") {
                margin-right: -55vw;
            }
            &.small {
                margin-right: -66vw;
                @include media(">=sm") {
                    margin-right: -45vw;
                }
            }
            &.active {
                margin-right: 0;
            }
            .closeSideMenu {
                right: 100%;
                padding-right: 10px;
                &:hover {
                    @include vendor-prefix("border-radius", "0 50% 50% 0");
                }
            }
        }
        &.side-right {
            left: 0;
            margin-left: -86vw;
            @include media(">=sm") {
                margin-left: -55vw;
            }
            &.small {
                margin-left: -56vw;
                @include media(">=sm") {
                    margin-left: -35vw;
                }
            }
            &.active {
                margin-left: 0;
            }
            .closeSideMenu {
                left: 100%;
                padding-left: 10px;
                &:hover {
                    @include vendor-prefix("border-radius", "50% 0 0 50%");
                }
            }
        }
        @include media("<=md") {
            #sidebar {
                margin-right: -86vw;
            }
            #sidebar.active {
                margin-right: 0;
            }
            #content {
                width: 100%;
            }
            #content.active {
                width: calc(100% - 86vw);
            }
        }
    }
}
.sidebar {
    width: 86vw;
    visibility: hidden;
    @include media(">=sm") {
        width: 55vw;
    }
    &.small {
        width: 66vw;
        @include media(">=sm") {
            width: 45vw;
        }
    }
    position: fixed;
    top: 0;
    height: 100vh;
    z-index: 9999;
    background: #00508b;
    color: #fff;
    overflow-y: auto;
    @include vendor-prefix("transition", "all 0.3s");
    .closeSideMenu {
        display: none;
        color: #fff;
        font-weight: 300;
        font-family: Arial, sans-serif;
        background: #00508b;
        padding: 5px;
        position: absolute;
        top: 5px;
        @include vendor-prefix("border-radius", "50%");
        font-weight: bold;
        text-shadow: 0 0 5px #fff;
        transition: all 0.3s;
        padding: 2px 10px;
        i {
            font-size: 1.2em;
        }
    }
    &.active {
        visibility: visible;
        .closeSideMenu {
            display: inline-block;
        }
    }
}
</style>
<script lang="ts">
import { Component, Vue, Prop, Ref } from "vue-property-decorator";

@Component
export default class SideBar extends Vue {
    @Prop({ type: Boolean, default: false }) public isOpen: boolean;
    @Prop({ type: String, default: "left" }) public side: string;
    @Prop({ type: String }) public cls: string;
    @Ref("sidebar") public sidebar: HTMLDivElement;

    public opend: boolean = this.isOpen;

    public open() {
        // this.sidebar.classList.remove('d-none');
        this.opend = true;
    }

    public close() {
        this.opend = false;
    }

    public toggle() {
        // console.log((this.$refs.sidebar as HTMLDivElement).classList);
        // this.sidebar.classList.remove('d-none');
        this.opend = !this.opend;
    }

    mounted() {
        document.body.addEventListener("click", event => {
            if (
                !(
                    this.$el == event.target ||
                    this.$el.contains(event.target as Node)
                )
            ) {
                if (
                    this.opend &&
                    !(event.target as HTMLElement).hasAttribute("side-opener")
                )
                    this.close();
            }
        });
    }
}
</script>
