@Props(['lang' => true, 'href', 'slug'])

<a {{$attributes}} @if ($lang)
    href="{{LaravelLocalization::localizeUrl($href)}}" @else href="{{$href}}"
    @endif @if (request()->is('*/products/*') && isset($slug))
    v-on:click.prevent.stop="h.d.getProducts('/collection/{{$slug}}')"
     :class="{active: h.d.activeSlug === '{{$slug}}'}" @endif>
    {{$slot}}
</a>