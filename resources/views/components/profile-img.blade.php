@props(['src', 'width' => 70, 'height' => 70, 'vue' => true, 'cls' => 'mx-auto'])

<div class="rounded-circle overflow-hidden x-pimg {{$cls}}" style="height: {{$height}}px; width: {{$width}}px; background-size:cover;
background-position:center; border-radius:50%; @if(!$vue) background-image: url({{Str::startsWith($src, '/') ? '' : '/'}}{{$src}}) @endif" 
@if ($vue):style="'background-image: url(' + {{$src}} + ')'" @endif>
</div>