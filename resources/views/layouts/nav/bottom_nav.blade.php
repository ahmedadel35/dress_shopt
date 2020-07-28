<div class="d-none d-md-block" id='bottom-nav'>
    <ul class="nav nav-pills nav-fill" style="background-color: #e3f2fd"
        id="myTab" role="tablist">
        <li class="nav-item">
            <x-a class="nav-link" href="/">
                {{__('All Categories')}}
            </x-a>
        </li>
        @foreach ($cats as $c)
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#"
                role="button" aria-haspopup="true" aria-expanded="false">
                {{__('cat.'.$c->title)}}
            </a>
            <div class="dropdown-menu">
                <x-a class="dropdown-item" :href="'/products/' .$c->slug"
                    :slug="$c->slug">
                    {{__('cat.' .$c->title)}}
                </x-a>
                @foreach ($c->sub_cats as $sc)
                <x-a class="dropdown-item" :href="'/products/' .$sc->slug"
                    :slug="$sc->slug">
                    {{__($sc->title)}}
                </x-a>
                @endforeach
            </div>
        </li>
        @endforeach
    </ul>
</div>
<div class="d-block d-sm-none bg-primary col-12 py-2" id="nav-form">
    @include('products.index.search-form', ['sm' => ''])
</div>