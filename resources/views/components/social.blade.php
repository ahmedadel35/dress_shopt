@props(['class' => 'btn-outline-primary', 'placement' =>
'', 'product' => 'mp'])

<ul class="nav text-center {{$placement}}">
    {{$slot}}
    <li class="nav-item">
        <a class="nav-link btn {{$class}}" target="_blank"
            href="https://www.facebook.com/sharer.php?u={{url()->current()}}">
            <i class="fab fa-facebook-f"></i>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link btn {{$class}}" target="_blank"
            :href="'https://twitter.com/intent/tweet?url={{url()->current()}}&text={{__('t.show.shareTxt')}}' + h.d.{{$product}}.title">
            <i class="fab fa-twitter"></i>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link btn {{$class}}" target="_blank"
            :href="'https://www.linkedin.com/shareArticle?mini=true&url={{url()->current()}}&title={{__('t.show.shareTxt')}}' + h.d.{{$product}}.title">
            <i class="fab fa-linkedin"></i>
        </a>
    </li>
</ul>