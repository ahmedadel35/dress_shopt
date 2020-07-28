@isset($cats)
@foreach ($cats as $c)
<li class="nav-item">
    <x-collapse :title="__('cat.'.$c->title)" class="nav-link notSuper"
        v-on:click="h.d.addClassRemoveFromAll($event, 'nav-link.notSuper')">
        <ul class="nav flex-column nav-pills list-group list-group-flush">
            @foreach ($c->sub_cats as $sc)
            <x-a class="nav-link break-word child list-group-item bg-transparent"
                v-on:click="h.d.addClassRemoveFromAll($event, 'nav-link.child')"
                :href="'/products/' .$sc->slug" :slug="$sc->slug">
                {{__($sc->title)}}
            </x-a>
            @endforeach
        </ul>
    </x-collapse>
</li>
@endforeach
@endisset