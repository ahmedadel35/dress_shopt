<?php

namespace App\Console\Commands;

trait VueFilesTxt
{
    private static $App = <<<EOT
import { Vue } from 'vue-property-decorator';
import Axios from 'axios';
import Home from './pages/home';

Axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
Axios.interceptors.response.use(
    function(response) {
        // TODO show loader
        console.log(response.data);
        return response;
    },
    function(error) {
        // TODO hide loader
        console.log(error);
        return Promise.reject(error);
    }
);

Vue.config.productionTip = false;

const app = new Vue({
    el: '#app',
    components: {
        Home,
    }
});    
EOT;

    private static $indexHtml = <<<'EOD'
<div :id="this.$options.name.toLowerCase() + '-page'">
    <slot v-bind:d="d">
    </slot>
</div>
EOD;

    private static $super = <<<'TS'
import { Vue, Component } from 'vue-property-decorator';

export interface SuperData {
}

@Component({
    template: require('./index-template.html')
})
export default class Super extends Vue {
    public d: SuperData = {};

    /**
     * attach compoenent properties and methods to global d variable
     *
     * @param self current component instance
     * @param methods array of public methods
     */
    protected attachToGlobal(self: Super, methods: string[]) {
        for (const k in self.$data) {
            if (k === "d") {
                continue;
            }
            this.d[k] = this.$data[k];
        }

        methods.map(x => {
            this.d[x] = self[x];
        });
    }

    protected addClass(selector: string, cls: string) {
        const el = document.querySelector(selector) as HTMLElement;
        if (!el) return;
        el.classList.add(cls);
    }

    protected removeClass(selector: string, cls: string) {
        const el = document.querySelector(selector) as HTMLElement;
        if (!el) return;
        el.classList.remove(cls);
    }

    beforeMount() {}
}
TS;

    private static $home = <<<'TS'
import { Component, Prop } from 'vue-property-decorator';
import Super from './super';

export interface HomeData {}

@Component
export default class Home extends Super {
    public d: HomeData = {
        // all your compnent data will be present in here

    };
    
    public log() {
        console.log('log is working');
    }

    beforeMount() {
        this.attachToGlobal(this, ['log']);
    }
}
TS;

    private static $tsconfig = <<<'JSN'
{
    "compileOnSave": false,
    "compilerOptions": {
        "baseUrl": "./",
        "sourceMap": true,
        "declaration": false,
        "allowJs": true,
        "strictNullChecks": true,
        "downlevelIteration": true,
        "emitDecoratorMetadata": true,
        "experimentalDecorators": true,
        "importHelpers": true,
        "target": "es5",
        "module": "es2015",
        "moduleResolution": "node",
        "typeRoots": ["node_modules/@types"],
        "lib": ["dom", "es6"]
    }
}
JSN;

    private static $mix = <<<MX
const mix = require('laravel-mix');

mix.ts('resources/js/app.ts', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    // .browserSync('laravel.test')
    .version();
MX;

    private static $layout = <<<'HML'
<{{$cpt ?? ''}}>
    <template v-slot="h">
        {{-- you can access js data in here as @{{h.d.(varible name)}} --}}
        @yield('content')
    </template>
</{{$cpt ?? ''}}>
HML;

    private function getNewPageTxt($pageName): string
    {
        return <<<TS
import { Component } from 'vue-property-decorator';
import Super from './super';

export interface {$pageName}Data {}

@Component
export default class $pageName extends Super {
    public d: {$pageName}Data = {};

    beforeMount() {
        this.attachToGlobal(this, []);
    }
}       
TS;
    }

    private function getInterfaceTxt($pageName): string
    {
        return <<<TS
export default interface {$pageName}Interface {

}
TS;
    }

    private function getComponentTxt($pageName, $breakpoints = '$breakpoints'): string
    {
        return <<<TS
<template>
</template>
<script lang="ts">
import { Vue, Component, Prop, Watch, Ref } from "vue-property-decorator";

@Component
export default class $pageName extends Vue { }
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
TS;
    }

    private function getMixinTxt($pageName): string
    {
        return <<<TS
import { Vue, Component, Prop, Watch } from "vue-property-decorator";
import Super from "../pages/super";

@Component
export default class $pageName extends Super { }
TS;
    }
}
