@spaceless
    <!doctype html>
    <html dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}"
        lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="keywords"
            content="shop,ecommerce,ecommerce dress,clothes,dress for her, her,ملابس , ملابس محجبات , ملابس لها">
        <meta name="description" content="Dress online shop">
        <meta name="author" content="Ahmed Adel">
        <link rel="apple-touch-icon" sizes="180x180" href="/icon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/icon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/icon/favicon-16x16.png">
        <link rel="manifest" href="/icon/site.webmanifest">
        <link rel="mask-icon" href="/icon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">

        <title>@yield('title')</title>

        <style>
            .sk-cube-grid {
                width: 40px;
                height: 40px;
                margin: 100px auto
            }

            .sk-cube-grid .sk-cube {
                width: 33%;
                height: 33%;
                background-color: #fff;
                float: left;
                -webkit-animation: sk-cubeGridScaleDelay 1.3s infinite ease-in-out;
                animation: sk-cubeGridScaleDelay 1.3s infinite ease-in-out
            }

            .sk-cube-grid .sk-cube1 {
                -webkit-animation-delay: .2s;
                animation-delay: .2s
            }

            .sk-cube-grid .sk-cube2 {
                -webkit-animation-delay: .3s;
                animation-delay: .3s
            }

            .sk-cube-grid .sk-cube3 {
                -webkit-animation-delay: .4s;
                animation-delay: .4s
            }

            .sk-cube-grid .sk-cube4 {
                -webkit-animation-delay: .1s;
                animation-delay: .1s
            }

            .sk-cube-grid .sk-cube5 {
                -webkit-animation-delay: .2s;
                animation-delay: .2s
            }

            .sk-cube-grid .sk-cube6 {
                -webkit-animation-delay: .3s;
                animation-delay: .3s
            }

            .sk-cube-grid .sk-cube7 {
                -webkit-animation-delay: 0s;
                animation-delay: 0s
            }

            .sk-cube-grid .sk-cube8 {
                -webkit-animation-delay: .1s;
                animation-delay: .1s
            }

            .sk-cube-grid .sk-cube9 {
                -webkit-animation-delay: .2s;
                animation-delay: .2s
            }

            @-webkit-keyframes sk-cubeGridScaleDelay {

                0%,
                100%,
                70% {
                    -webkit-transform: scale3D(1, 1, 1);
                    transform: scale3D(1, 1, 1)
                }

                35% {
                    -webkit-transform: scale3D(0, 0, 1);
                    transform: scale3D(0, 0, 1)
                }
            }

            <blade keyframes|%20sk-cubeGridScaleDelay%20%7B%0D>0%,
            100%,
            70% {
                -webkit-transform: scale3D(1, 1, 1);
                transform: scale3D(1, 1, 1)
            }

            35% {
                -webkit-transform: scale3D(0, 0, 1);
                transform: scale3D(0, 0, 1)
            }
            }

            code,
            pre.line-numbers {
                direction: ltr
            }

        </style>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Cairo&display=swap" rel="stylesheet">

        <!-- Styles -->
        @if(app()->isLocale('ar'))
            <link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.2.1/css/bootstrap.min.css"
                integrity="sha384-vus3nQHTD+5mpDiZ4rkEPlnkcyTP+49BhJ4wJeJunw06ZAp+wzzeBPUXr42fi8If"
                crossorigin="anonymous">
            <style>

            </style>
        @else
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
                integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
                crossorigin="anonymous">
        @endif

        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        <script type="text/javascript" src="https://cdn.jsdelivr.net/gh/thednp/minifill@0.0.4/dist/minifill.min.js">
        </script>
        <script src="https://cdn.jsdelivr.net/npm/es6-promise@4/dist/es6-promise.auto.min.js"></script>
        <!--[if IE]>
<script src="node_modules/@webcomponents/webcomponentsjs/webcomponents-bundle.js"></script>
<![endif]-->
        <style>


        </style>
    </head>

    <body>
        <div id="app">
            <{{ $cpt ?? 'cart' }} ref="childCmp">
                <template v-slot="h">
                    @include('layouts.nav.index')

                    <main class="{{ $class ?? 'py-4' }} container-fluid"
                        id="component-container">
                        @include('sidebar.left')
                        @yield('content')
                        @include('sidebar.right')
                    </main>
                    @include('footer')
                </template>
            </{{ $cpt ?? 'cart' }}>
        </div>
        <div style="position: fixed; top: 0;left: 0; z-index:9999; width: 100%; height: 100%; background-color: #343a40"
            id="splashScreen">
            <div style="position: relative">
                <div style="display: flex; justify-content: center; align-items: center;height: 100vh;">
                    <div class="sk-cube-grid">
                        <div class="sk-cube sk-cube1"></div>
                        <div class="sk-cube sk-cube2"></div>
                        <div class="sk-cube sk-cube3"></div>
                        <div class="sk-cube sk-cube4"></div>
                        <div class="sk-cube sk-cube5"></div>
                        <div class="sk-cube sk-cube6"></div>
                        <div class="sk-cube sk-cube7"></div>
                        <div class="sk-cube sk-cube8"></div>
                        <div class="sk-cube sk-cube9"></div>
                    </div>
                </div>
            </div>
        </div>
    </body>

    @stack('scripts')
    <script>
        window['xjs'] = window['xjs'] || {};
        xjs['xlang'] = [
            "{{ __('t.show.alertTitle') }}", // HeadsUp // 0
            "{{ __('t.show.dangerTitle') }}", // 1
            "{{ __('t.show.succTitle') }}", // done  // 2
            "{{ __('t.show.warnTitle') }}", // warning  // 3
            "{{ __('t.show.errMess') }}", // 4
            "{{ __('t.show.succMess') }}", // 5
            "{{ __('t.index.productNoAmount') }}", // 6
            "{{ __('js.product_exists') }}", // 7
            "{{ __('js.quickviewBtn') }}", // 8
            "{{ __('js.addToCart') }}", // 9
            "{{ __('js.youSave') }}", // 10
            "{{ __('t.offTxt') }}", // 11
            "{{ __('js.mailSuccess') }}", // 12
            "{{ __('js.addressDeleteFail') }}", // 13
            "{{ __('js.outOfStock') }}", // 14
        ];

    </script>
    <script src="{{ mix('js/app.js') }}" defer>
    </script>
    <script src="{{ asset('js/bootstrap-native-v4.min.js') }}" defer></script>

    </html>
@endspaceless
