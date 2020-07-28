@props(['title', 'slug', 'loader' => true, 'height' => 16,])

<h5 {{$attributes}} id="productModalLabel">
    @isset ($slug)
    <a v-if="{{$title}}" v-text="{{$title}}"
        :href="'/{{app()->getLocale()}}/product/' + {{$slug}}">
    </a>
    @else
    <span v-if="{{$title}}" v-text="{{$title}}"></span>
    @endisset

    @if ($loader)
    <div class="overflow-hidden" style="max-height: {{$height}}px"  v-else>
        <span class="w-75">
            <content-loader :width="50" :height="{{$height}}"
                primary-color="#cbcbcd" secondary-color="#02103b">
                <rect x="0" y="0" rx="0" ry="0" width="50"
                    height="{{$height}}" />
            </content-loader>
        </span>
    </div>
    @endif
</h5>