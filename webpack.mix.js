const mix = require("laravel-mix");

require('laravel-mix-polyfill');

if (!mix.inProduction()) {
    mix.webpackConfig({
        devtool: "source-map"
    });
}

mix.options({
    extractVueStyles: true
})
    .sourceMaps()
    .ts("resources/js/app.ts", "public/js")
    .sass("resources/sass/app.scss", "public/css")
    // .browserSync('laravel.test')
    // .polyfill({
    //     enabled: true,
    //     useBuiltIns: "usage",
    //     targets: {"ie": 10},
    //     debug: true
    //  })
    .version();
